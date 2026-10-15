<?php
declare(strict_types=1);

/**
 * Abrechnung eines abgeschlossenen Vormonats: summiert die erfassten
 * Stunden, verschickt eine E-Mail an den Kassenwart (CC: Trainer selbst)
 * und sperrt den Monat danach fuer weitere Aenderungen/erneute Abrechnung.
 * Der aktuelle Monat kann nicht abgerechnet werden - Stunden werden nur 1x
 * im Monat, fuer den jeweils abgeschlossenen Vormonat, abgerechnet.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/TrainerSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';
require_once __DIR__ . '/lib/Mailer.php';
require_once __DIR__ . '/lib/JsonResponse.php';
require_once __DIR__ . '/lib/RechnungBuilder.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-abrechnen.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

function respond(int $httpCode, array $payload): never
{
    JsonResponse::send($httpCode, $payload);
}

/** Formatiert Stunden mit Komma statt Punkt, ohne unnoetige Nachkommastellen (1 statt 1,00; 1,5 statt 1,50). */
function formatStundenDe(float $stunden): string
{
    return rtrim(rtrim(number_format($stunden, 2, ',', ''), '0'), ',');
}

/**
 * Waehlt den Stundensatz fuer den abgerechneten Monat (nicht fuer den
 * Zeitpunkt der Abrechnung): bis einschliesslich 09/2026 galt ein
 * einheitlicher Satz, ab 10/2026 ein eigener Satz je Trainerrolle
 * (trainer['rolle'], per Admin-Uebersicht gesetzt - siehe
 * trainer-rolle-setzen.php). Unabhaengig davon, ob der Trainer die PDF-
 * Rechnung bekommt (das entscheidet allein HAUPTTRAINER_EMAIL) - es kann
 * mehrere Trainer mit Rolle "haupttrainer" geben.
 */
function stundensatzFuer(string $monat, string $rolle): float
{
    if ($monat < '2026-10') {
        return TRAINER_STUNDENSATZ;
    }
    return $rolle === 'aushilfstrainer' ? TRAINER_STUNDENSATZ_AUSHILFSTRAINER : TRAINER_STUNDENSATZ_HAUPTTRAINER;
}

/**
 * Empfaenger der Abrechnungsmail: Kassenwart (NOTIFY_EMAIL) und der Trainer
 * selbst im 'An', Kontakt@ (CONTACT_EMAIL) in CC. Doppelte Adressen werden
 * entfernt (z. B. wenn der Trainer selbst der Kassenwart ist).
 *
 * @return array{0: string, 1: array<int,string>, 2: array<int,string>} [An-Hauptempfaenger, weitere An, CC]
 */
function abrechnungsEmpfaenger(string $trainerEmail): array
{
    $gesehen = [mb_strtolower(NOTIFY_EMAIL) => true];
    $weitereAn = [];
    if (!isset($gesehen[mb_strtolower($trainerEmail)])) {
        $weitereAn[] = $trainerEmail;
        $gesehen[mb_strtolower($trainerEmail)] = true;
    }
    $cc = [];
    $kontakt = defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'Kontakt@boxring-wetterau.de';
    if (!isset($gesehen[mb_strtolower($kontakt)])) {
        $cc[] = $kontakt;
    }
    return [NOTIFY_EMAIL, $weitereAn, $cc];
}

TrainerSession::start();
$trainerId = TrainerSession::currentTrainerId();
if ($trainerId === null) {
    respond(401, ['success' => false, 'message' => 'Bitte einloggen.']);
}
$trainer = TrainerStore::findTrainerById($trainerId);
if ($trainer === null || $trainer['status'] !== 'aktiv') {
    respond(401, ['success' => false, 'message' => 'Bitte einloggen.']);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

$input = json_decode((string) file_get_contents('php://input'), true);
$monat = is_array($input) && isset($input['monat']) && is_string($input['monat']) ? $input['monat'] : '';

if (!preg_match('/^\d{4}-\d{2}$/', $monat) || DateTime::createFromFormat('Y-m', $monat) === false) {
    respond(400, ['success' => false, 'message' => 'Ungueltiger Monat.']);
}

$aktuellerMonat = (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('Y-m');
if ($monat >= $aktuellerMonat) {
    respond(422, ['success' => false, 'message' => 'Der aktuelle Monat kann erst im Folgemonat abgerechnet werden - bitte einen abgeschlossenen Vormonat waehlen.']);
}

$eintraege = TrainerStore::stundenFuerMonat($trainerId, $monat);
usort($eintraege, static fn($a, $b) => $a['datum'] <=> $b['datum']);
$stundenGesamt = array_sum(array_map(static fn($e) => $e['stunden'], $eintraege));

if ($stundenGesamt <= 0) {
    respond(422, ['success' => false, 'message' => 'Fuer diesen Monat wurden keine Stunden erfasst.']);
}

// Reserviert den Monat ATOMAR, bevor die (langsame) Mail verschickt wird -
// verhindert, dass zwei zeitgleiche Abrechnen-Klicks (Doppelklick, zwei
// offene Tabs) beide die Mail verschicken, bevor einer von beiden merkt,
// dass der andere schon abgerechnet hat.
if (!TrainerStore::abrechnungReservieren($trainerId, $monat)) {
    respond(422, ['success' => false, 'message' => 'Dieser Monat wurde bereits abgerechnet.']);
}

// Bricht PHP mittendrin ab (Zeitlimit, Fatal Error), laeuft der catch unten nie.
// Dann die Reservierung hier freigeben, sonst haengt der Monat auf "wird verschickt".
$abrechnungFertig = false;
register_shutdown_function(static function () use (&$abrechnungFertig, $trainerId, $monat): void {
    if (!$abrechnungFertig) {
        error_log('trainer-abrechnen.php: Abbruch vor Abschluss, Reservierung ' . $trainerId . '/' . $monat . ' wird freigegeben');
        TrainerStore::abrechnungStornieren($trainerId, $monat);
    }
});

$name = $trainer['vorname'] . ' ' . $trainer['nachname'];
// Bekommt die PDF-Rechnung statt der einfachen Text-Mail - unabhaengig von
// der Abrechnungsrolle (trainer['rolle']), die nur den Stundensatz bestimmt.
$bekommtPdfRechnung = strcasecmp($trainer['email'], HAUPTTRAINER_EMAIL) === 0;
$stundensatz = stundensatzFuer($monat, $trainer['rolle'] ?? 'haupttrainer');
$betrag = round($stundenGesamt * $stundensatz, 2);

$monatsName = (new DateTimeImmutable($monat . '-01'))->format('m/Y');
[$empfaenger, $weitereAn, $ccKontakt] = abrechnungsEmpfaenger($trainer['email']);

try {
    if ($bekommtPdfRechnung) {
        // Haupttrainer ist umsatzsteuerpflichtig und bekommt eine echte
        // PDF-Rechnung statt der einfachen Text-Mail - an Kassenwart und
        // Trainer selbst (An), Kontakt@ in CC.
        $rechnungPdf = RechnungBuilder::build([
            'monat' => $monat,
            'stunden' => $stundenGesamt,
            'betragBrutto' => $betrag,
            'rechnungsdatum' => new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')),
        ]);
        Mailer::send(
            $empfaenger,
            'Kassenwart',
            'Rechnung Trainerstunden ' . $monatsName . ' – ' . $name,
            '<p>Hallo,</p><p>im Anhang die Rechnung für die Trainerstunden ' . htmlspecialchars($monatsName, ENT_QUOTES, 'UTF-8') . '.</p><p>Sportliche Grüße,<br>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</p>',
            ['name' => 'Rechnung', 'content' => $rechnungPdf, 'filename' => 'Rechnung-' . str_replace('-', '', $monat) . '_BRW.pdf'],
            $ccKontakt,
            $weitereAn,
            SMTP_USER // Absender = Login-Postfach, damit Absender nie gleich Empfaenger ist
        );
    } else {
        $zeilen = '';
        foreach ($eintraege as $eintrag) {
            $tagFormatiert = (new DateTimeImmutable($eintrag['datum']))->format('d.m.Y');
            $zeilen .= '<tr><td style="padding:4px 12px 4px 0;">' . htmlspecialchars($tagFormatiert, ENT_QUOTES, 'UTF-8') . '</td><td style="padding:4px 0;">' . formatStundenDe($eintrag['stunden']) . ' Std.</td></tr>';
        }

        $bodyHtml = sprintf(
            '<p>Hallo,</p>' .
            '<p><strong>%s</strong> rechnet die Trainerstunden fuer <strong>%s</strong> ab:</p>' .
            '<table style="border-collapse:collapse;">%s</table>' .
            '<p><strong>Gesamt: %s Stunden × %s €/Std. = %s €</strong></p>' .
            '<p>Sportliche Gruesse,<br>Boxring Wetterau 1983 e.V. – Trainer-Zeiterfassung</p>',
            htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($monatsName, ENT_QUOTES, 'UTF-8'),
            $zeilen,
            formatStundenDe($stundenGesamt),
            number_format($stundensatz, 2, ',', '.'),
            number_format($betrag, 2, ',', '.')
        );

        Mailer::send(
            $empfaenger,
            'Kassenwart',
            'Trainerabrechnung ' . $monatsName . ' – ' . $name,
            $bodyHtml,
            null,
            $ccKontakt,
            $weitereAn,
            SMTP_USER // Absender = Login-Postfach, damit Absender nie gleich Empfaenger ist
        );
    }
} catch (Throwable $e) {
    error_log('trainer-abrechnen.php: Mail fehlgeschlagen: ' . $e->getMessage());
    // Reservierung wieder aufheben, damit der Trainer es erneut versuchen kann statt dauerhaft ausgesperrt zu sein.
    TrainerStore::abrechnungStornieren($trainerId, $monat);
    $abrechnungFertig = true;
    respond(500, ['success' => false, 'message' => 'Abrechnung konnte nicht verschickt werden. Bitte versuch es erneut oder melde dich direkt unter ' . NOTIFY_EMAIL . '.']);
}

// Mail ist raus: ab hier darf die Reservierung nicht mehr freigegeben werden (sonst Doppelabrechnung).
$abrechnungFertig = true;
try {
    TrainerStore::abrechnungAbschliessen($trainerId, $monat, $stundenGesamt, $betrag);
} catch (Throwable $e) {
    error_log('trainer-abrechnen.php: Abschluss nicht gespeichert (Mail wurde verschickt): ' . $e->getMessage());
}

respond(200, ['success' => true, 'stunden' => $stundenGesamt, 'betrag' => $betrag]);
