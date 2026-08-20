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

$betrag = round($stundenGesamt * TRAINER_STUNDENSATZ, 2);

$monatsName = (new DateTimeImmutable($monat . '-01'))->format('m/Y');
$name = $trainer['vorname'] . ' ' . $trainer['nachname'];

$zeilen = '';
foreach ($eintraege as $eintrag) {
    $tagFormatiert = (new DateTimeImmutable($eintrag['datum']))->format('d.m.Y');
    $zeilen .= '<tr><td style="padding:4px 12px 4px 0;">' . htmlspecialchars($tagFormatiert, ENT_QUOTES, 'UTF-8') . '</td><td style="padding:4px 0;">' . $eintrag['stunden'] . ' Std.</td></tr>';
}

$bodyHtml = sprintf(
    '<p>Hallo,</p>' .
    '<p><strong>%s</strong> rechnet die Trainerstunden fuer <strong>%s</strong> ab:</p>' .
    '<table style="border-collapse:collapse;">%s</table>' .
    '<p><strong>Gesamt: %d Stunden × %s €/Std. = %s €</strong></p>' .
    '<p>Sportliche Gruesse,<br>Boxring Wetterau 1983 e.V. – Trainer-Zeiterfassung</p>',
    htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($monatsName, ENT_QUOTES, 'UTF-8'),
    $zeilen,
    $stundenGesamt,
    number_format(TRAINER_STUNDENSATZ, 2, ',', '.'),
    number_format($betrag, 2, ',', '.')
);

try {
    Mailer::send(
        NOTIFY_EMAIL,
        'Kassenwart',
        'Trainerabrechnung ' . $monatsName . ' – ' . $name,
        $bodyHtml,
        null,
        [$trainer['email']]
    );
} catch (Throwable $e) {
    error_log('trainer-abrechnen.php: Mail fehlgeschlagen: ' . $e->getMessage());
    // Reservierung wieder aufheben, damit der Trainer es erneut versuchen kann statt dauerhaft ausgesperrt zu sein.
    TrainerStore::abrechnungStornieren($trainerId, $monat);
    respond(500, ['success' => false, 'message' => 'Abrechnung konnte nicht verschickt werden. Bitte versuch es erneut oder melde dich direkt unter ' . NOTIFY_EMAIL . '.']);
}

TrainerStore::abrechnungAbschliessen($trainerId, $monat, $stundenGesamt, $betrag);

respond(200, ['success' => true, 'stunden' => $stundenGesamt, 'betrag' => $betrag]);
