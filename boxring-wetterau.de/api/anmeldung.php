<?php
declare(strict_types=1);

/**
 * Verarbeitet die Online-Mitgliedsanmeldung aus mitglied-werden.html:
 * validiert, berechnet den anteiligen Beitrag, traegt die Anmeldung in ein
 * Google Sheet ein, baut das ausgefuellte PDF und verschickt es per E-Mail
 * an das neue Mitglied sowie zur Kenntnis an den Verein.
 *
 * Jeder der drei Verarbeitungsschritte (Sheet, PDF, Mail) wird einzeln
 * abgesichert, damit ein Fehler in einem Schritt nicht dazu fuehrt, dass die
 * Anmeldung komplett verloren geht.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/Validation.php';
require_once __DIR__ . '/lib/Beitrag.php';
require_once __DIR__ . '/lib/GoogleSheetsAppender.php';
require_once __DIR__ . '/lib/PdfFormBuilder.php';
require_once __DIR__ . '/lib/Mailer.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('anmeldung.php: config.php fehlt - siehe config.sample.php');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet. Bitte kontaktiere uns direkt.']);
    exit;
}
require_once $configPath;

function respond(int $httpCode, array $payload): void
{
    http_response_code($httpCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

$raw = file_get_contents('php://input');
$input = json_decode((string) $raw, true);
if (!is_array($input)) {
    respond(400, ['success' => false, 'message' => 'Ungueltige Anfrage.']);
}

// Honeypot: Bots fuellen versteckte Felder aus, echte Nutzer nie.
if (!empty($input['website'])) {
    respond(200, ['success' => true]);
}

$data = [];
foreach ([
    'name', 'vorname', 'strasse', 'hausnummer', 'plz', 'ort', 'beruf', 'geburtstag',
    'telefon', 'email', 'erziehungsberechtigter', 'beitrag', 'unterschrift_ort',
    'unterschrift_datum', 'signatur_antrag', 'kontoinhaber_gleich_antragsteller',
    'kontoinhaber_name', 'kontoinhaber_strasse', 'kontoinhaber_ort', 'iban',
    'signatur_sepa', 'sepa_bestaetigt', 'kuendigung_gelesen', 'satzung_anerkannt',
    'bilder_einwilligung', 'datenschutz_gelesen',
] as $key) {
    $data[$key] = Validation::clean(isset($input[$key]) && is_string($input[$key]) ? $input[$key] : null);
}

$errors = [];

foreach (['name', 'vorname', 'strasse', 'hausnummer', 'plz', 'ort', 'geburtstag', 'telefon', 'email', 'unterschrift_ort', 'unterschrift_datum', 'signatur_antrag', 'signatur_sepa'] as $required) {
    if ($data[$required] === null) {
        $errors[] = "Pflichtfeld fehlt: $required";
    }
}

if ($data['email'] !== null && !Validation::emailValid($data['email'])) {
    $errors[] = 'E-Mail-Adresse ist ungueltig.';
}

if ($data['geburtstag'] !== null && !Validation::dateValid($data['geburtstag'])) {
    $errors[] = 'Geburtsdatum ist ungueltig.';
} elseif ($data['geburtstag'] !== null) {
    // Auch inhaltlich pruefen (nicht nur Formatvaliditaet) - die Client-
    // Pruefung in mitglied-werden.html laesst sich umgehen.
    $geburtstagDt = new DateTimeImmutable($data['geburtstag']);
    $stichtagDt = ($data['unterschrift_datum'] !== null && Validation::dateValid($data['unterschrift_datum']))
        ? new DateTimeImmutable($data['unterschrift_datum'])
        : new DateTimeImmutable();
    $alter = $stichtagDt->diff($geburtstagDt)->y;
    if ($geburtstagDt > $stichtagDt || $alter > 120) {
        $errors[] = 'Geburtsdatum ist unrealistisch (z. B. in der Zukunft).';
    }
}

if ($data['kuendigung_gelesen'] !== 'ja' || $data['satzung_anerkannt'] !== 'ja' || $data['sepa_bestaetigt'] !== 'ja' || $data['datenschutz_gelesen'] !== 'ja') {
    $errors[] = 'Bitte alle Pflicht-Bestaetigungen ankreuzen.';
}

if ($data['iban'] === null || !Validation::ibanValid($data['iban'])) {
    $errors[] = 'IBAN ist ungueltig.';
}

if ($data['unterschrift_datum'] !== null && !Validation::dateValid($data['unterschrift_datum'])) {
    $errors[] = 'Datum ist ungueltig.';
}

if ($data['kontoinhaber_gleich_antragsteller'] !== 'ja') {
    foreach (['kontoinhaber_name', 'kontoinhaber_strasse', 'kontoinhaber_ort'] as $required) {
        if ($data[$required] === null) {
            $errors[] = "Pflichtfeld fehlt: $required";
        }
    }
}

if (!empty($errors)) {
    respond(422, ['success' => false, 'message' => 'Bitte pruefe deine Angaben: ' . implode(' ', $errors)]);
}

$data['iban'] = strtoupper(str_replace(' ', '', $data['iban']));

// Beitragsart wird serverseitig aus dem Geburtsdatum bestimmt - der vom
// Client mitgeschickte Wert ist nur eine Anzeige-Vorschau und nie verbindlich.
$anteiligerBeitrag = null;
if ($data['unterschrift_datum'] !== null && $data['geburtstag'] !== null) {
    try {
        $beitrittsdatum = new DateTimeImmutable($data['unterschrift_datum']);
        $data['beitrag'] = Beitrag::ausAlter(new DateTimeImmutable($data['geburtstag']), $beitrittsdatum);
        $anteiligerBeitrag = Beitrag::anteiligerBeitrag($data['beitrag'], $beitrittsdatum);
    } catch (Throwable $e) {
        error_log('anmeldung.php: Beitragsberechnung fehlgeschlagen: ' . $e->getMessage());
    }
}
$data['anteiliger_beitrag'] = $anteiligerBeitrag;
// Explizite Zeitzone, da der Server standardmaessig in UTC laeuft - ohne das
// hier steht z.B. 15:31 statt 17:31 im PDF (Sommerzeit CEST = UTC+2).
$data['eingereicht_am'] = (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('d.m.Y H:i');

// Ab hier auf deutsches Anzeigeformat (tt.mm.jjjj) umstellen - das <input
// type="date">-Feld liefert ISO (jjjj-mm-tt), das brauchte oben noch die
// Beitragsberechnung. PDF und Google-Sheet-Eintrag sollen aber tt.mm.jjjj zeigen.
foreach (['geburtstag', 'unterschrift_datum'] as $dateKey) {
    if ($data[$dateKey] !== null) {
        $data[$dateKey] = (new DateTimeImmutable($data[$dateKey]))->format('d.m.Y');
    }
}

// Reihenfolge des Gesamtprozesses (so vom Verein festgelegt):
// 1. PDF aus den geprueften Formulardaten bauen
// 2. Kopie per Mail ans neue Mitglied
// 3. Kopie per Mail an Kassenwart + Kontakt (CC: 1. Vorsitzender)
// 4. Erst danach: Eintrag mit neuer, automatisch vergebener Mitgliedsnummer
//    in "Boxring Wetterau - Mitgliederliste" (Google Sheet)

$pdfContent = null;
try {
    $pdfContent = PdfFormBuilder::build($data);
} catch (Throwable $e) {
    error_log('anmeldung.php: PDF-Erstellung fehlgeschlagen: ' . $e->getMessage());
}

$pdfFilename = 'Aufnahmeantrag-' . preg_replace('/[^A-Za-z0-9_-]/', '', $data['name'] . '-' . $data['vorname']) . '.pdf';

$memberMailOk = false;
try {
    $bodyHtml = sprintf(
        '<p>Hallo %s,</p>' .
        '<p>herzlich willkommen im Boxring Wetterau 1983 e.V.! Wir freuen uns sehr, dass du jetzt Teil unseres Vereins bist.</p>' .
        '<p>Im Anhang findest du dein ausgefülltes Aufnahmeformular als PDF – bitte einmal in Ruhe gegenprüfen, ob alle Angaben stimmen.</p>' .
        '<p>Eine Sache noch in eigener Sache: Die einmalige Aufnahmegebühr von 20,- Euro wird zusammen mit deinem ersten ' .
        'Mitgliedsbeitrag automatisch per SEPA-Lastschrift von dem angegebenen Konto eingezogen.</p>' .
        '<p>Bei Fragen melde dich jederzeit gerne unter <a href="mailto:%s">%s</a>.</p>' .
        '<p>Wir freuen uns auf dich im Training!</p>' .
        '<p>Sportliche Grüße,<br>Boxring Wetterau 1983 e.V.</p>',
        htmlspecialchars($data['vorname'], ENT_QUOTES, 'UTF-8'),
        NOTIFY_EMAIL,
        NOTIFY_EMAIL
    );
    // CC: Kontaktadresse des Vereins + Kassenwart (Absender bekommt sonst
    // selbst keine digitale Kopie der Anmeldung/des PDFs). Doppelte Adresse
    // vermeiden, falls beide Konstanten in config.php identisch sind.
    $ccEmails = array_values(array_unique(array_map('strtolower', [MEMBER_CC_EMAIL, NOTIFY_EMAIL])));
    Mailer::send(
        $data['email'],
        $data['vorname'] . ' ' . $data['name'],
        'Willkommen beim Boxring Wetterau 1983 e.V.!',
        $bodyHtml,
        $pdfContent !== null ? ['name' => 'Aufnahmeantrag', 'content' => $pdfContent, 'filename' => $pdfFilename] : null,
        $ccEmails
    );
    $memberMailOk = true;
} catch (Throwable $e) {
    error_log('anmeldung.php: Mail an Mitglied fehlgeschlagen: ' . $e->getMessage());
}

// Bewusst KEINE separate interne Benachrichtigungsmail: Die CC auf der
// Willkommensmail (MEMBER_CC_EMAIL + NOTIFY_EMAIL/Kassenwart) ist die einzige
// Benachrichtigung an den Verein. Zwei getrennte Mails an teils dieselben
// Adressen fuehrten sonst zu doppeltem Empfang (z.B. wenn Kontakt@ auf ein
// persoenliches Postfach weiterleitet, das gleichzeitig als CC/Empfaenger
// eingetragen ist).

$sheetOk = false;
try {
    $sheets = new GoogleSheetsAppender(GOOGLE_SHEETS_WEBAPP_URL, GOOGLE_SHEETS_WEBAPP_SECRET);

    // Spaltenreihenfolge MUSS exakt zur Kopfzeile von "Boxring Wetterau -
    // Mitgliederliste" passen: gekuendigt Jahresende, Status, Vorname,
    // Nachname, IBAN, Beitrag, Mitgliedsnr, Mandatsref, Zahlungspflichtiger,
    // Strasse, PLZ, Ort, Beruf, Telefon, Mail, Geburtstag, Eintritt,
    // Anmeldegebuehr Zahldatum. Mitgliedsnr wird vom Apps Script vergeben
    // (Platzhalter '' hier wird dort ueberschrieben). Mandatsref/
    // Anmeldegebuehr-Zahldatum bleiben leer - die vergibt/pflegt der
    // Kassenwart von Hand. Online-Anmeldungen sind immer "aktive" - die
    // Unterscheidung Aktiv/Passiv gibt es fuer neue Mitglieder nicht mehr,
    // das war nur eine Alt-Kategorie im Bestand.
    $status = 'aktive';
    $zahlungspflichtiger = $data['kontoinhaber_gleich_antragsteller'] === 'ja'
        ? ''
        : ($data['kontoinhaber_name'] ?? '');

    $neueMitgliedsnr = $sheets->appendRow([
        '',
        $status,
        $data['vorname'],
        $data['name'],
        $data['iban'],
        Beitrag::jahresbetrag($data['beitrag']),
        '',
        '',
        $zahlungspflichtiger,
        trim(($data['strasse'] ?? '') . ' ' . ($data['hausnummer'] ?? '')),
        $data['plz'],
        $data['ort'],
        $data['beruf'],
        $data['telefon'],
        $data['email'],
        $data['geburtstag'],
        $data['unterschrift_datum'],
        '',
    ]);
    $sheetOk = true;
} catch (Throwable $e) {
    error_log('anmeldung.php: Google Sheets append fehlgeschlagen: ' . $e->getMessage());
}

if ($sheetOk || $memberMailOk) {
    respond(200, ['success' => true]);
}

respond(500, ['success' => false, 'message' => 'Deine Anmeldung konnte nicht verarbeitet werden. Bitte versuch es erneut oder melde dich direkt unter ' . NOTIFY_EMAIL . '.']);
