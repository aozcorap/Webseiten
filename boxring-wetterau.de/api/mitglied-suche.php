<?php
declare(strict_types=1);

/**
 * Sucht ein Mitglied per Vor- und/oder Nachname (Teilstring, jeweils
 * optional) in "Boxring Wetterau - Mitgliederliste" (Google Sheet), fuer
 * den Trainer-Adminbereich (mitglied-check.html). Nur nach Login
 * (AdminSession) erreichbar. Liefert bewusst nur unkritische Eckdaten -
 * siehe GoogleSheetsAppender::search() und mitgliederliste.gs.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/AdminSession.php';
require_once __DIR__ . '/lib/Validation.php';
require_once __DIR__ . '/lib/GoogleSheetsAppender.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('mitglied-suche.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

AdminSession::start();

if (!AdminSession::isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Bitte zuerst einloggen.']);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode nicht erlaubt.']);
    exit;
}

$input = json_decode((string) file_get_contents('php://input'), true);
$vorname = Validation::clean(is_array($input) && isset($input['vorname']) && is_string($input['vorname']) ? $input['vorname'] : null);
$nachname = Validation::clean(is_array($input) && isset($input['nachname']) && is_string($input['nachname']) ? $input['nachname'] : null);

if ($vorname === null && $nachname === null) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Bitte Vor- oder Nachnamen angeben.']);
    exit;
}

try {
    $sheets = new GoogleSheetsAppender(GOOGLE_SHEETS_WEBAPP_URL, GOOGLE_SHEETS_WEBAPP_SECRET);
    $result = $sheets->search($vorname ?? '', $nachname ?? '');
    echo json_encode([
        'success' => true,
        'gefunden' => $result['gefunden'],
        'treffer' => $result['treffer'],
    ]);
} catch (Throwable $e) {
    error_log('mitglied-suche.php: Suche fehlgeschlagen: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Suche fehlgeschlagen. Bitte spaeter erneut versuchen.']);
}
