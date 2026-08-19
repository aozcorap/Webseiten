<?php
declare(strict_types=1);

/** Prueft, ob im Trainer-Zeiterfassungsbereich bereits eine gueltige Session besteht. */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/TrainerSession.php';
require_once __DIR__ . '/lib/TrainerStore.php';
TrainerSession::start();

$trainerId = TrainerSession::currentTrainerId();
if ($trainerId === null) {
    echo json_encode(['loggedIn' => false]);
    exit;
}

$trainer = TrainerStore::findTrainerById($trainerId);
if ($trainer === null || $trainer['status'] !== 'aktiv') {
    TrainerSession::logout();
    echo json_encode(['loggedIn' => false]);
    exit;
}

echo json_encode(['loggedIn' => true, 'vorname' => $trainer['vorname'], 'nachname' => $trainer['nachname']]);
