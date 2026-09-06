<?php
declare(strict_types=1);

/** Logout aus dem Shop-Adminbereich (shop/index.html). */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/ShopAdminSession.php';
ShopAdminSession::start();
ShopAdminSession::logout();

echo json_encode(['success' => true]);
