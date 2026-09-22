<?php
declare(strict_types=1);

/**
 * Gemeinsame Session-Handhabung fuer den Trainer-Adminbereich
 * (mitglied-check.html + api/mitglied-*.php). Ein einzelnes, geteiltes
 * Trainer-Passwort (ADMIN_PASSWORD in config.php) statt einzelner Accounts -
 * fuer den Zweck (schneller Mitglied-Check waehrend des Trainings) reicht
 * das, mehr Aufwand (Nutzerverwaltung) waere hier unverhaeltnismaessig.
 */
final class AdminSession
{
    private const SESSION_KEY = 'mitglied_check_auth';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        // Eigener Cookie-Name, getrennt von TrainerSession - sonst teilen sich
        // beide Bereiche ueber den PHP-Standardnamen (PHPSESSID) dasselbe
        // $_SESSION-Array (nur mit unterschiedlichen Keys). Seit trainer-
        // zeiterfassung.html Trainer- UND Admin-Login auf derselben Seite
        // anbietet (gemeinsam genutzte Traininsraum-Geraete!), wuerde das
        // sonst dazu fuehren, dass ein danach eingeloggter Trainer die
        // vorherige Admin-Session desselben Browsers "erbt" und Admin-
        // Endpunkte ansprechen kann, ohne das Admin-Passwort zu kennen.
        session_name('bw_admin_sess');
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 8, // 8 Stunden - reicht fuer ein Training, nicht dauerhaft eingeloggt
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function login(): void
    {
        session_regenerate_id(true);
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
