<?php
declare(strict_types=1);

/**
 * Registrierung eines neuen Trainer-Accounts fuer die Zeiterfassung
 * (trainer-zeiterfassung.html). Legt den Account mit Status "pending" an
 * und schickt dem Kassenwart eine E-Mail mit einem Genehmigen/Ablehnen-Link
 * (kein Admin-Login noetig, siehe trainer-genehmigen.php).
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/Validation.php';
require_once __DIR__ . '/lib/TrainerStore.php';
require_once __DIR__ . '/lib/Mailer.php';
require_once __DIR__ . '/lib/JsonResponse.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-register.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

function respond(int $httpCode, array $payload): never
{
    JsonResponse::send($httpCode, $payload);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

$input = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($input)) {
    respond(400, ['success' => false, 'message' => 'Ungueltige Anfrage.']);
}

// Honeypot: Bots fuellen versteckte Felder aus, echte Nutzer nie.
if (!empty($input['website'])) {
    respond(200, ['success' => true]);
}

$vorname = Validation::clean(isset($input['vorname']) && is_string($input['vorname']) ? $input['vorname'] : null);
$nachname = Validation::clean(isset($input['nachname']) && is_string($input['nachname']) ? $input['nachname'] : null);
$email = Validation::clean(isset($input['email']) && is_string($input['email']) ? $input['email'] : null);
$passwort = isset($input['passwort']) && is_string($input['passwort']) ? $input['passwort'] : '';

$errors = [];
if ($vorname === null) {
    $errors[] = 'Vorname fehlt.';
}
if ($nachname === null) {
    $errors[] = 'Nachname fehlt.';
}
if ($email === null || !Validation::emailValid($email)) {
    $errors[] = 'E-Mail-Adresse ist ungueltig.';
}
if (strlen($passwort) < 8) {
    $errors[] = 'Passwort muss mindestens 8 Zeichen lang sein.';
}
if (!empty($errors)) {
    respond(422, ['success' => false, 'message' => implode(' ', $errors)]);
}

if (TrainerStore::findTrainerByEmail($email) !== null) {
    respond(422, ['success' => false, 'message' => 'Fuer diese E-Mail-Adresse existiert bereits ein Account. Bitte einloggen oder ' . NOTIFY_EMAIL . ' kontaktieren.']);
}

$approveToken = bin2hex(random_bytes(32));
$approveTokenExpiry = (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->modify('+30 days')->format('c');
$passwordHash = password_hash($passwort, PASSWORD_DEFAULT);

$baseUrl = 'https://www.boxring-wetterau.de/api/trainer-genehmigen.php';
$genehmigenUrl = $baseUrl . '?token=' . $approveToken . '&aktion=genehmigen';
$ablehnenUrl = $baseUrl . '?token=' . $approveToken . '&aktion=ablehnen';

// Erst die Kassenwart-Mail verschicken, den Account erst danach anlegen:
// schlaegt die Mail fehl, gibt es sonst einen "pending"-Account, dessen
// Genehmigungslink nie irgendwo ankam - der wuerde die E-Mail-Adresse
// dauerhaft fuer eine erneute Registrierung blockieren.
try {
    // Buttons als Tabelle statt inline-block nebeneinander - robuster in
    // E-Mail-Clients (z.B. Outlook ignoriert inline-block teils komplett).
    // Bewusst auffaellig (Grossbuchstaben, Haekchen/Kreuz-Symbol, deutliche
    // Farbflaechen), damit auf den ersten Blick klar ist: hier ist eine
    // Entscheidung noetig, nicht nur eine Info-Mail.
    $bodyHtml = sprintf(
        '<p>Hallo,</p>' .
        '<p><strong>%s %s</strong> (%s) hat sich fuer die Trainer-Zeiterfassung registriert und wartet auf Freigabe.</p>' .
        '<p style="font-weight:700;margin-bottom:12px;">Bitte jetzt entscheiden:</p>' .
        '<table cellpadding="0" cellspacing="0" style="margin-bottom:16px;"><tr>' .
        '<td style="padding-right:12px;"><a href="%s" style="display:inline-block;background:#2e9e4f;color:#ffffff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;letter-spacing:0.3px;">✓ TRAINER BESTAETIGEN</a></td>' .
        '<td><a href="%s" style="display:inline-block;background:#e8394f;color:#ffffff;padding:14px 28px;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;letter-spacing:0.3px;">✗ ABLEHNEN</a></td>' .
        '</tr></table>' .
        '<p>Der Link ist 30 Tage gueltig.</p>',
        htmlspecialchars($vorname, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($nachname, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($genehmigenUrl, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($ablehnenUrl, ENT_QUOTES, 'UTF-8')
    );
    // Geht an Kassenwart UND Vorstand (CONTACT_EMAIL) - "Vorstand oder
    // Kassenwart" soll beide in die Lage versetzen, freizugeben, nicht nur
    // eine feste Adresse. Gleichrangige Empfaenger (additionalTo), nicht CC,
    // damit z.B. Antworten nicht automatisch nur an einen von beiden gehen.
    $genehmigungsEmpfaenger = strcasecmp(NOTIFY_EMAIL, CONTACT_EMAIL) !== 0 ? [CONTACT_EMAIL] : [];
    Mailer::send(NOTIFY_EMAIL, 'Kassenwart', 'Neue Trainer-Registrierung: ' . $vorname . ' ' . $nachname, $bodyHtml, null, [], $genehmigungsEmpfaenger);
} catch (Throwable $e) {
    error_log('trainer-register.php: Mail an Kassenwart/Vorstand fehlgeschlagen: ' . $e->getMessage());
    respond(500, ['success' => false, 'message' => 'Registrierung konnte nicht abgeschlossen werden. Bitte versuch es erneut oder melde dich direkt unter ' . NOTIFY_EMAIL . '.']);
}

// Atomarer Check-and-Insert (siehe TrainerStore::createTrainerIfEmailFree) -
// verhindert, dass zwei zeitgleiche Registrierungen mit derselben E-Mail
// (Race zwischen dem findTrainerByEmail()-Check oben und dem Anlegen hier)
// beide durchkommen und zwei Accounts fuer eine Adresse entstehen.
if (TrainerStore::createTrainerIfEmailFree($vorname, $nachname, $email, $passwordHash, $approveToken, $approveTokenExpiry) === null) {
    respond(422, ['success' => false, 'message' => 'Fuer diese E-Mail-Adresse existiert bereits ein Account. Bitte einloggen oder ' . NOTIFY_EMAIL . ' kontaktieren.']);
}

respond(200, ['success' => true]);
