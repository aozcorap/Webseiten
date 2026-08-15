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
    'kontoinhaber_name', 'kontoinhaber_strasse', 'kontoinhaber_ort', 'iban', 'bic',
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

if (!in_array($data['beitrag'], ['aktive_150', 'passive_30', 'jugend_75'], true)) {
    $errors[] = 'Bitte eine gueltige Beitragsart auswaehlen.';
}

if ($data['kuendigung_gelesen'] !== 'ja' || $data['satzung_anerkannt'] !== 'ja' || $data['sepa_bestaetigt'] !== 'ja' || $data['datenschutz_gelesen'] !== 'ja') {
    $errors[] = 'Bitte alle Pflicht-Bestaetigungen ankreuzen.';
}

if ($data['iban'] === null || !Validation::ibanValid($data['iban'])) {
    $errors[] = 'IBAN ist ungueltig.';
}

if ($data['bic'] === null || !Validation::bicValid($data['bic'])) {
    $errors[] = 'BIC ist ungueltig.';
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
$data['bic'] = strtoupper(str_replace(' ', '', $data['bic']));

$anteiligerBeitrag = null;
if ($data['unterschrift_datum'] !== null) {
    try {
        $anteiligerBeitrag = Beitrag::anteiligerBeitrag($data['beitrag'], new DateTimeImmutable($data['unterschrift_datum']));
    } catch (Throwable $e) {
        error_log('anmeldung.php: Beitragsberechnung fehlgeschlagen: ' . $e->getMessage());
    }
}
$data['anteiliger_beitrag'] = $anteiligerBeitrag;
$data['eingereicht_am'] = (new DateTimeImmutable())->format('d.m.Y H:i');

$sheetOk = false;
try {
    $sheets = new GoogleSheetsAppender(GOOGLE_SERVICE_ACCOUNT_JSON_PATH, GOOGLE_SHEET_ID, GOOGLE_SHEET_RANGE);
    $sheets->appendRow([
        $data['eingereicht_am'],
        $data['name'],
        $data['vorname'],
        $data['strasse'],
        $data['hausnummer'],
        $data['plz'],
        $data['ort'],
        $data['beruf'],
        $data['geburtstag'],
        $data['telefon'],
        $data['email'],
        $data['erziehungsberechtigter'],
        Beitrag::label($data['beitrag']),
        $anteiligerBeitrag !== null ? number_format($anteiligerBeitrag, 2, ',', '.') : '',
        $data['bilder_einwilligung'] === 'ja' ? 'ja' : 'nein',
        $data['kontoinhaber_gleich_antragsteller'] === 'ja' ? 'ja' : 'nein',
        $data['kontoinhaber_name'],
        $data['kontoinhaber_strasse'],
        $data['kontoinhaber_ort'],
        $data['iban'],
        $data['bic'],
        $data['signatur_antrag'],
        $data['signatur_sepa'],
        $data['unterschrift_ort'],
        $data['unterschrift_datum'],
    ]);
    $sheetOk = true;
} catch (Throwable $e) {
    error_log('anmeldung.php: Google Sheets append fehlgeschlagen: ' . $e->getMessage());
}

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
        '<p>Hallo %s,</p><p>vielen Dank für deine Anmeldung beim Boxring Wetterau 1983 e.V.! ' .
        'Im Anhang findest du dein ausgefülltes Aufnahmeformular als PDF – bitte einmal in Ruhe gegenprüfen. ' .
        'Bei Fragen melde dich einfach unter <a href="mailto:%s">%s</a>.</p><p>Sportliche Grüße<br>Boxring Wetterau 1983 e.V.</p>',
        htmlspecialchars($data['vorname'], ENT_QUOTES, 'UTF-8'),
        NOTIFY_EMAIL,
        NOTIFY_EMAIL
    );
    Mailer::send(
        $data['email'],
        $data['vorname'] . ' ' . $data['name'],
        'Deine Anmeldung beim Boxring Wetterau 1983 e.V.',
        $bodyHtml,
        $pdfContent !== null ? ['name' => 'Aufnahmeantrag', 'content' => $pdfContent, 'filename' => $pdfFilename] : null
    );
    $memberMailOk = true;
} catch (Throwable $e) {
    error_log('anmeldung.php: Mail an Mitglied fehlgeschlagen: ' . $e->getMessage());
}

try {
    $internalBodyHtml = sprintf(
        '<p>Neue Online-Anmeldung: <strong>%s %s</strong> (%s), Beitragsart: %s%s.</p>' .
        '<p>Sheet-Eintrag: %s · PDF: %s · Mail an Mitglied: %s</p>',
        htmlspecialchars($data['vorname'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars(Beitrag::label($data['beitrag']) ?? $data['beitrag'], ENT_QUOTES, 'UTF-8'),
        $anteiligerBeitrag !== null ? sprintf(' (anteilig %.2f Euro)', $anteiligerBeitrag) : '',
        $sheetOk ? 'ok' : 'FEHLGESCHLAGEN, siehe Server-Log',
        $pdfContent !== null ? 'ok' : 'FEHLGESCHLAGEN, siehe Server-Log',
        $memberMailOk ? 'ok' : 'FEHLGESCHLAGEN, siehe Server-Log'
    );
    Mailer::send(
        NOTIFY_EMAIL,
        NOTIFY_NAME,
        'Neue Mitgliedsanmeldung: ' . $data['vorname'] . ' ' . $data['name'],
        $internalBodyHtml,
        $pdfContent !== null ? ['name' => 'Aufnahmeantrag', 'content' => $pdfContent, 'filename' => $pdfFilename] : null
    );
} catch (Throwable $e) {
    error_log('anmeldung.php: interne Benachrichtigungsmail fehlgeschlagen: ' . $e->getMessage());
}

if ($sheetOk || $memberMailOk) {
    respond(200, ['success' => true]);
}

respond(500, ['success' => false, 'message' => 'Deine Anmeldung konnte nicht verarbeitet werden. Bitte versuch es erneut oder melde dich direkt unter ' . NOTIFY_EMAIL . '.']);
