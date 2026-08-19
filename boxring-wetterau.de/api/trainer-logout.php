<?php
declare(strict_types=1);

/** Logout aus der Trainer-Zeiterfassung. */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/TrainerSession.php';
TrainerSession::start();
TrainerSession::logout();

echo json_encode(['success' => true]);
