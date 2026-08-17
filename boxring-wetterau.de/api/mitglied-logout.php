<?php
declare(strict_types=1);

/** Logout aus dem Trainer-Adminbereich (mitglied-check.html) - wichtig bei geteilten Geraeten in der Halle. */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/AdminSession.php';
AdminSession::start();
AdminSession::logout();

echo json_encode(['success' => true]);
