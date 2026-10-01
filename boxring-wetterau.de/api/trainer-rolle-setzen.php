<?php
declare(strict_types=1);

/**
 * Setzt die Abrechnungsrolle eines Trainer-Accounts (Admin-Uebersicht in
 * trainer-zeiterfassung.html): "aushilfstrainer" oder "haupttrainer" -
 * entscheidet in trainer-abrechnen.php, welcher Stundensatz greift (siehe
 * stundensatzFuer() dort). Unabhaengig von HAUPTTRAINER_EMAIL in config.php,
 * das nur bestimmt, wer die PDF-Rechnung statt der einfachen Text-Mail
 * bekommt - es kann mehrere Trainer mit Rolle "haupttrainer" geben, von
 * denen nur einer (HAUPTTRAINER_EMAIL) die Rechnung stellt.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/AdminSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';
require_once __DIR__ . '/lib/JsonResponse.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-rolle-setzen.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

AdminSession::start();

function respond(int $httpCode, array $payload): never
{
    JsonResponse::send($httpCode, $payload);
}

if (!AdminSession::isLoggedIn()) {
    respond(401, ['success' => false, 'message' => 'Nicht eingeloggt.']);
}
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

$input = json_decode((string) file_get_contents('php://input'), true);
$id = is_array($input) && isset($input['id']) && is_int($input['id']) ? $input['id'] : null;
$rolle = is_array($input) && isset($input['rolle']) && is_string($input['rolle']) ? $input['rolle'] : '';

if ($id === null || !in_array($rolle, ['aushilfstrainer', 'haupttrainer'], true)) {
    respond(400, ['success' => false, 'message' => 'Ungueltige Anfrage.']);
}
if (TrainerStore::findTrainerById($id) === null) {
    respond(404, ['success' => false, 'message' => 'Trainer nicht gefunden.']);
}

TrainerStore::setTrainerRolle($id, $rolle);

respond(200, ['success' => true]);
