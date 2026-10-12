<?php
declare(strict_types=1);

require_once __DIR__ . '/JsonFileStore.php';

/**
 * Einfaches Rate-Limit pro Client-IP (Gleitfenster), gespeichert in
 * api/data/ratelimit.json (per .htaccess gesperrt, per .gitignore nicht im
 * Repo). Schuetzt oeffentliche Mail-Endpunkte und Logins vor Massenmissbrauch.
 *
 * Bewusst nur REMOTE_ADDR: X-Forwarded-For waere vom Client faelschbar.
 * Hinter einem Reverse-Proxy sehen alle Besucher dieselbe IP - dann muss
 * clientKey() angepasst werden.
 */
final class RateLimit
{
    private const PATH = __DIR__ . '/../data/ratelimit.json';

    /** Zaehlt einen Versuch. Gibt false zurueck, wenn das Limit damit ueberschritten ist. */
    public static function hit(string $bucket, int $max, int $windowSeconds): bool
    {
        return self::run($bucket, $windowSeconds, true, $max);
    }

    /** Prueft nur, ohne zu zaehlen. true = noch Platz. */
    public static function allowed(string $bucket, int $max, int $windowSeconds): bool
    {
        return self::run($bucket, $windowSeconds, false, $max);
    }

    public static function clear(string $bucket): void
    {
        $key = self::key($bucket);
        JsonFileStore::withLock(self::PATH, ['b' => []], function (array $data) use ($key) {
            unset($data['b'][$key]);
            return [$data, null];
        });
    }

    private static function run(string $bucket, int $window, bool $count, int $max): bool
    {
        $key = self::key($bucket);
        $now = time();
        try {
            return JsonFileStore::withLock(self::PATH, ['b' => []], function (array $data) use ($key, $now, $window, $count, $max) {
                // Abgelaufene Eintraege aller Buckets aufraeumen (Datei klein halten).
                foreach ($data['b'] as $k => $stamps) {
                    $data['b'][$k] = array_values(array_filter($stamps, fn($t) => is_int($t) && $t > $now - 86400));
                    if ($data['b'][$k] === []) {
                        unset($data['b'][$k]);
                    }
                }
                $recent = array_values(array_filter($data['b'][$key] ?? [], fn($t) => $t > $now - $window));
                if ($count) {
                    $recent[] = $now;
                    $data['b'][$key] = $recent;
                    return [$data, count($recent) <= $max];
                }
                return [$data, count($recent) < $max];
            });
        } catch (Throwable $e) {
            // Fail-open: ein defekter Limiter darf Anmeldungen nicht komplett blockieren.
            error_log('RateLimit: ' . $e->getMessage());
            return true;
        }
    }

    private static function key(string $bucket): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        return $bucket . ':' . substr(hash('sha256', $ip), 0, 24);
    }
}
