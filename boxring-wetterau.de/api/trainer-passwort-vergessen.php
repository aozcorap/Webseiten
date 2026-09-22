<?php
declare(strict_types=1);

/**
 * "Passwort vergessen" fuer Trainer-Accounts (trainer-zeiterfassung.html).
 * Verschickt bei bekannter E-Mail einen Reset-Link. Antwort ist bewusst
 * immer dieselbe, egal ob die E-Mail existiert - sonst liesse sich damit
 * erraten, welche E-Mail-Adressen als Trainer registriert sind.
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
    error_log('trainer-passwort-vergessen.php: config.php fehlt');
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
$email = is_array($input) && isset($input['email']) && is_string($input['email']) ? Validation::clean($input['email']) : null;

$genericSuccess = ['success' => true, 'message' => 'Falls ein Trainer-Account mit dieser E-Mail-Adresse existiert, wurde soeben ein Link zum Zuruecksetzen des Passworts verschickt.'];

if ($email === null || !Validation::emailValid($email)) {
    respond(200, $genericSuccess);
}

$trainer = TrainerStore::findTrainerByEmail($email);
if ($trainer === null) {
    respond(200, $genericSuccess);
}

$resetToken = bin2hex(random_bytes(32));
$resetTokenExpiry = (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->modify('+2 hours')->format('c');
TrainerStore::setResetToken($trainer['id'], $resetToken, $resetTokenExpiry);

$resetUrl = 'https://www.boxring-wetterau.de/trainer-zeiterfassung.html?reset=' . $resetToken;

try {
    Mailer::send(
        $trainer['email'],
        $trainer['vorname'] . ' ' . $trainer['nachname'],
        'Passwort zuruecksetzen – Trainer-Zeiterfassung',
        '<p>Hallo ' . htmlspecialchars($trainer['vorname'], ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>fuer deinen Trainer-Account wurde ein neues Passwort angefordert. Klick auf den folgenden Link, um ein neues Passwort zu vergeben:</p>'
            . '<p><a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '">Neues Passwort vergeben</a></p>'
            . '<p>Der Link ist 2 Stunden gueltig. Falls du das nicht warst, kannst du diese E-Mail ignorieren - dein Passwort bleibt dann unveraendert.</p>'
            . '<p>Sportliche Gruesse,<br>Boxring Wetterau 1983 e.V.</p>'
    );
} catch (Throwable $e) {
    error_log('trainer-passwort-vergessen.php: Mail fehlgeschlagen: ' . $e->getMessage());
    respond(200, $genericSuccess);
}

respond(200, $genericSuccess);
