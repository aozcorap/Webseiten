<?php
declare(strict_types=1);

/** Setzt per Reset-Token (siehe trainer-passwort-vergessen.php) ein neues Passwort und loggt direkt ein. */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/TrainerSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';
require_once __DIR__ . '/lib/JsonResponse.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-passwort-reset.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

TrainerSession::start();

function respond(int $httpCode, array $payload): never
{
    JsonResponse::send($httpCode, $payload);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

$input = json_decode((string) file_get_contents('php://input'), true);
$token = is_array($input) && isset($input['token']) && is_string($input['token']) ? $input['token'] : '';
$passwort = is_array($input) && isset($input['passwort']) && is_string($input['passwort']) ? $input['passwort'] : '';

if ($token === '') {
    respond(400, ['success' => false, 'message' => 'Ungueltiger Link.']);
}
if (strlen($passwort) < 8) {
    respond(422, ['success' => false, 'message' => 'Passwort muss mindestens 8 Zeichen lang sein.']);
}

$trainer = TrainerStore::findTrainerByResetToken($token);
if ($trainer === null) {
    respond(400, ['success' => false, 'message' => 'Dieser Link ist nicht mehr gueltig. Bitte fordere einen neuen an.']);
}

$tokenExpiry = new DateTimeImmutable($trainer['resetTokenExpiry']);
if ($tokenExpiry < new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin'))) {
    respond(400, ['success' => false, 'message' => 'Dieser Link ist abgelaufen. Bitte fordere einen neuen an.']);
}

TrainerStore::setPassword($trainer['id'], password_hash($passwort, PASSWORD_DEFAULT));

$eingeloggt = $trainer['status'] === 'aktiv';
if ($eingeloggt) {
    TrainerSession::login($trainer['id']);
}

respond(200, ['success' => true, 'loggedIn' => $eingeloggt, 'vorname' => $trainer['vorname'], 'nachname' => $trainer['nachname']]);
