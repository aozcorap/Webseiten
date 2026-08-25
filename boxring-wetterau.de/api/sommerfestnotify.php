<?php
declare(strict_types=1);

/**
 * Einfache Benachrichtigungsmail bei einer neuen Sommerfest-Anmeldung
 * (brw-sommerfest/index.html, gehostet auf GitHub Pages). Die Anmeldung
 * selbst liegt in Supabase - diese Mail ist nur ein zusaetzlicher Hinweis
 * fuers Nachtracking (z.B. bei PayPal-Zahlungsproblemen), kein Ersatz dafuer.
 * Nutzt denselben Mailer/SMTP wie die Mitgliedsanmeldung (anmeldung.php).
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

// Endpunkt wird von der auf GitHub Pages gehosteten Sommerfest-Seite
// aufgerufen (anderer Origin) - CORS nur fuer diesen einen Endpunkt, alle
// anderen api/-Dateien bleiben same-origin.
$allowedOrigins = [
    'https://aozcorap.github.io',
];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
}
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/Validation.php';
require_once __DIR__ . '/lib/Mailer.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('sommerfest-notify.php: config.php fehlt - siehe config.sample.php');
    echo json_encode(['success' => false]);
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
    respond(405, ['success' => false]);
}

$raw = file_get_contents('php://input');
$input = json_decode((string) $raw, true);
if (!is_array($input)) {
    respond(400, ['success' => false]);
}

// Honeypot: Bots fuellen versteckte Felder aus, echte Nutzer nie.
if (!empty($input['website'])) {
    respond(200, ['success' => true]);
}

$name = Validation::clean(isset($input['name']) && is_string($input['name']) ? $input['name'] : null) ?? 'Sommerfest-Anmeldung';
$personen = isset($input['personen']) ? (int) $input['personen'] : 1;
$kinder = isset($input['kinder']) ? (int) $input['kinder'] : 0;
$vegetarisch = !empty($input['vegetarisch']);
$vegPersonen = isset($input['vegPersonen']) ? (int) $input['vegPersonen'] : 0;
$salat = !empty($input['salat']);
$kuchen = !empty($input['kuchen']);
$anmerkung = Validation::clean(isset($input['anmerkung']) && is_string($input['anmerkung']) ? $input['anmerkung'] : null);
$pfand = number_format($personen * 5, 2, ',', '.') . ' €';

$zeilen = [
    'Name: ' . $name,
    'Personen: ' . $personen . ' (davon ' . $kinder . ' Kinder unter 12)',
    'Vegetarisch: ' . ($vegetarisch ? 'ja (' . $vegPersonen . ' Personen)' : 'nein'),
    'Bringt mit: ' . trim(($salat ? 'Salat ' : '') . ($kuchen ? 'Kuchen' : '')) ?: 'nichts angegeben',
    'Pfand (PayPal): ' . $pfand,
];
if ($anmerkung !== null) {
    $zeilen[] = 'Anmerkung: ' . $anmerkung;
}

$bodyHtml = '<p>Neue Sommerfest-Anmeldung:</p><p>' . implode('<br>', array_map(
    fn(string $z): string => htmlspecialchars($z, ENT_QUOTES, 'UTF-8'),
    $zeilen
)) . '</p><p>Details und Pfand-Status wie gewohnt im Admin-Bereich der Sommerfest-Seite.</p>';

try {
    Mailer::send(CONTACT_EMAIL, 'Boxring Wetterau 1983 e.V.', 'Neue Sommerfest-Anmeldung: ' . $name, $bodyHtml);
    respond(200, ['success' => true]);
} catch (Throwable $e) {
    error_log('sommerfest-notify.php: Mail fehlgeschlagen: ' . $e->getMessage());
    respond(500, ['success' => false]);
}
