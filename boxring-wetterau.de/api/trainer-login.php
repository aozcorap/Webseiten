<?php
declare(strict_types=1);

/** Login fuer einen einzelnen Trainer-Account (trainer-zeiterfassung.html). */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/TrainerSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';
require_once __DIR__ . '/lib/RateLimit.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-login.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

TrainerSession::start();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode nicht erlaubt.']);
    exit;
}

if (!RateLimit::allowed('login-trainer', 10, 900)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Zu viele Fehlversuche. Bitte in 15 Minuten erneut versuchen.']);
    exit;
}

$input = json_decode((string) file_get_contents('php://input'), true);
$email = is_array($input) && isset($input['email']) && is_string($input['email']) ? trim($input['email']) : '';
$passwort = is_array($input) && isset($input['passwort']) && is_string($input['passwort']) ? $input['passwort'] : '';

$trainer = $email !== '' ? TrainerStore::findTrainerByEmail($email) : null;

// Auch bei unbekannter E-Mail einen Hash pruefen, damit die Antwortzeit nicht
// verraet, ob die Adresse als Trainer registriert ist.
$hashZumVergleich = $trainer['passwordHash'] ?? password_hash('dummy-kein-account', PASSWORD_DEFAULT);
$passwortOk = password_verify($passwort, $hashZumVergleich);

if ($trainer === null || !$passwortOk) {
    RateLimit::hit('login-trainer', 10, 900);
    usleep(500_000);
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'E-Mail-Adresse oder Passwort falsch.']);
    exit;
}

if ($trainer['status'] === 'pending') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Dein Account wartet noch auf Freigabe durch den Vorstand/Kassenwart.']);
    exit;
}

if ($trainer['status'] === 'abgelehnt') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Dein Account wurde nicht freigeschaltet. Bitte kontaktiere den Kassenwart.']);
    exit;
}

RateLimit::clear('login-trainer');
TrainerSession::login($trainer['id']);
echo json_encode(['success' => true, 'vorname' => $trainer['vorname'], 'nachname' => $trainer['nachname']]);
