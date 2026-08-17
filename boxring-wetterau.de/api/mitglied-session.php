<?php
declare(strict_types=1);

/** Prueft, ob im Trainer-Adminbereich (mitglied-check.html) bereits eine gueltige Session besteht. */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/AdminSession.php';
AdminSession::start();

echo json_encode(['loggedIn' => AdminSession::isLoggedIn()]);
