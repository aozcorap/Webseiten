<?php
declare(strict_types=1);

/**
 * Session-Handhabung fuer den Shop-Adminbereich (shop/index.html Admin-Tab +
 * api/bestellungen.php). Eigenes, vom Trainer-Adminbereich (AdminSession)
 * getrenntes Passwort (SHOP_ADMIN_USERNAME/SHOP_ADMIN_PASSWORD in config.php),
 * da hier echte Kunden-E-Mail-Adressen sichtbar sind und geloescht werden
 * kann - bewusst nicht an den allgemeinen Trainer-Zugang gekoppelt.
 */
final class ShopAdminSession
{
    private const SESSION_KEY = 'shop_admin_auth';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 8,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function login(): void
    {
        $_SESSION[self::SESSION_KEY] = true;
    }

    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION[self::SESSION_KEY]);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
