<?php
declare(strict_types=1);

/** Liste aller Trainer-Accounts fuer die Admin-Uebersicht (trainer-verwaltung.html). */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/AdminSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-liste.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

AdminSession::start();
if (!AdminSession::isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt.']);
    exit;
}

$trainers = array_map(static function (array $trainer): array {
    return [
        'id' => $trainer['id'],
        'vorname' => $trainer['vorname'],
        'nachname' => $trainer['nachname'],
        'email' => $trainer['email'],
        'status' => $trainer['status'],
        'erstelltAm' => $trainer['erstelltAm'],
    ];
}, TrainerStore::alleTrainer());

echo json_encode(['success' => true, 'trainers' => $trainers]);
