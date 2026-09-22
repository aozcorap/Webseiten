<?php
declare(strict_types=1);

/**
 * Setzt den Status eines Trainer-Accounts manuell (Admin-Uebersicht in trainer-zeiterfassung.html) -
 * fuer Faelle, in denen der reguläre Genehmigen/Ablehnen-Link aus der
 * Registrierungsmail (trainer-genehmigen.php) nicht greift, z.B. weil die
 * Mail verpasst wurde oder ein Account nachtraeglich gesperrt werden soll.
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
    error_log('trainer-status-setzen.php: config.php fehlt');
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
$status = is_array($input) && isset($input['status']) && is_string($input['status']) ? $input['status'] : '';

if ($id === null || !in_array($status, ['pending', 'aktiv', 'abgelehnt'], true)) {
    respond(400, ['success' => false, 'message' => 'Ungueltige Anfrage.']);
}
if (TrainerStore::findTrainerById($id) === null) {
    respond(404, ['success' => false, 'message' => 'Trainer nicht gefunden.']);
}

TrainerStore::setTrainerStatus($id, $status);

respond(200, ['success' => true]);
