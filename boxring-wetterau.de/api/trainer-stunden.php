<?php
declare(strict_types=1);

/**
 * GET  ?monat=YYYY-MM   -> Stundeneintraege des eingeloggten Trainers fuer den Monat
 *                          + ob der Monat bereits abgerechnet ist (dann gesperrt).
 * POST {datum, stunden} -> Stunden fuer einen Tag speichern (stunden=0 loescht den Eintrag).
 *                          Nur volle Stunden, nur fuer nicht bereits abgerechnete Monate,
 *                          nicht fuer Tage in der Zukunft.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/Validation.php';
require_once __DIR__ . '/lib/TrainerSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('trainer-stunden.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

TrainerSession::start();
$trainerId = TrainerSession::currentTrainerId();
if ($trainerId === null) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bitte einloggen.']);
    exit;
}
$trainer = TrainerStore::findTrainerById($trainerId);
if ($trainer === null || $trainer['status'] !== 'aktiv') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bitte einloggen.']);
    exit;
}

function respond(int $httpCode, array $payload): void
{
    http_response_code($httpCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function monatValid(string $monat): bool
{
    return (bool) preg_match('/^\d{4}-\d{2}$/', $monat) && DateTime::createFromFormat('Y-m', $monat) !== false;
}

$method = $_SERVER['REQUEST_METHOD'] ?? '';

if ($method === 'GET') {
    $monat = isset($_GET['monat']) && is_string($_GET['monat']) ? $_GET['monat'] : '';
    if (!monatValid($monat)) {
        respond(400, ['success' => false, 'message' => 'Ungueltiger Monat.']);
    }

    $eintraege = TrainerStore::stundenFuerMonat($trainerId, $monat);
    $abrechnung = TrainerStore::abrechnungFuerMonat($trainerId, $monat);

    respond(200, [
        'success' => true,
        'eintraege' => array_map(static fn($e) => ['datum' => $e['datum'], 'stunden' => $e['stunden']], $eintraege),
        'abgerechnet' => $abrechnung !== null,
        'abrechnung' => $abrechnung,
    ]);
}

if ($method === 'POST') {
    $input = json_decode((string) file_get_contents('php://input'), true);
    $datum = is_array($input) && isset($input['datum']) && is_string($input['datum']) ? $input['datum'] : '';
    $stunden = is_array($input) && isset($input['stunden']) && is_numeric($input['stunden']) ? (int) $input['stunden'] : null;

    if (!Validation::dateValid($datum)) {
        respond(400, ['success' => false, 'message' => 'Ungueltiges Datum.']);
    }
    if ($stunden === null || $stunden < 0 || $stunden > 24) {
        respond(400, ['success' => false, 'message' => 'Bitte volle Stunden zwischen 0 und 24 eintragen.']);
    }

    $heute = new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin'));
    $tag = new DateTimeImmutable($datum);
    if ($tag > $heute) {
        respond(422, ['success' => false, 'message' => 'Stunden koennen nicht fuer die Zukunft erfasst werden.']);
    }

    $monat = substr($datum, 0, 7);
    if (TrainerStore::abrechnungFuerMonat($trainerId, $monat) !== null) {
        respond(422, ['success' => false, 'message' => 'Dieser Monat wurde bereits abgerechnet und kann nicht mehr geaendert werden.']);
    }

    TrainerStore::stundenSpeichern($trainerId, $datum, $stunden);
    respond(200, ['success' => true]);
}

respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
