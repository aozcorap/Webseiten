<?php
declare(strict_types=1);

/** Login fuer den Shop-Adminbereich (shop/index.html). Siehe ShopAdminSession.php. */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/ShopAdminSession.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('shop-login.php: config.php fehlt');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet.']);
    exit;
}
require_once $configPath;

ShopAdminSession::start();

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Methode nicht erlaubt.']);
    exit;
}

$input = json_decode((string) file_get_contents('php://input'), true);
$username = is_array($input) && isset($input['username']) && is_string($input['username']) ? $input['username'] : '';
$password = is_array($input) && isset($input['password']) && is_string($input['password']) ? $input['password'] : '';

if ($username === '' || $password === '' || !hash_equals(SHOP_ADMIN_USERNAME, $username) || !hash_equals(SHOP_ADMIN_PASSWORD, $password)) {
    usleep(500_000);
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Benutzername oder Passwort falsch.']);
    exit;
}

ShopAdminSession::login();
echo json_encode(['success' => true]);
