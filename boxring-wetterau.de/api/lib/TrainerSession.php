<?php
declare(strict_types=1);

/**
 * Session-Handhabung fuer einzelne Trainer-Accounts (trainer-zeiterfassung.html).
 * Getrennt von AdminSession (mitglied-check.html), da dort ein geteiltes
 * Passwort fuer alle Trainer genutzt wird, hier aber individuelle Accounts.
 */
final class TrainerSession
{
    private const SESSION_KEY = 'trainer_zeit_auth_id';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 24 * 30, // 30 Tage - Trainer sollen nicht staendig neu einloggen muessen
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function login(int $trainerId): void
    {
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = $trainerId;
    }

    public static function currentTrainerId(): ?int
    {
        $id = $_SESSION[self::SESSION_KEY] ?? null;
        return is_int($id) ? $id : null;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
