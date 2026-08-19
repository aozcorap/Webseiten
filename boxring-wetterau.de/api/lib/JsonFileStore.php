<?php
declare(strict_types=1);

/**
 * Generischer Datei-Speicher fuer JSON-Daten mit Locking gegen gleichzeitige
 * Schreibzugriffe (z.B. zwei Trainer speichern zeitgleich Stunden). Bewusst
 * keine echte Datenbank - fuer die zu erwartende Datenmenge (eine Handvoll
 * Trainer, taegliche Stundeneintraege) reichen gesperrte JSON-Dateien im
 * Ordner api/data/ (per .htaccess von aussen gesperrt), ohne dass auf dem
 * Server eine MySQL-Datenbank eingerichtet werden muss.
 */
final class JsonFileStore
{
    /**
     * Oeffnet die Datei exklusiv, liest den aktuellen Inhalt, ruft $mutator
     * mit den Daten (oder $default, falls Datei leer/neu) auf und schreibt
     * das Ergebnis zurueck - alles innerhalb derselben Sperre, damit kein
     * gleichzeitiger Schreibzugriff dazwischenfunken kann.
     *
     * $mutator bekommt die aktuellen Daten und muss ein Array
     * [$neueDaten, $rueckgabewert] liefern. $neueDaten === null bedeutet:
     * nichts aendern (nur lesen).
     */
    public static function withLock(string $path, array $default, callable $mutator): mixed
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0770, true);
        }

        $handle = fopen($path, 'c+');
        if ($handle === false) {
            throw new RuntimeException("Konnte Datendatei nicht oeffnen: $path");
        }

        try {
            if (!flock($handle, LOCK_EX)) {
                throw new RuntimeException("Konnte Datendatei nicht sperren: $path");
            }

            $raw = stream_get_contents($handle);
            $data = ($raw === false || trim((string) $raw) === '') ? $default : json_decode($raw, true);
            if (!is_array($data)) {
                $data = $default;
            }

            [$neueDaten, $rueckgabewert] = $mutator($data);

            if ($neueDaten !== null) {
                rewind($handle);
                ftruncate($handle, 0);
                fwrite($handle, json_encode($neueDaten, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                fflush($handle);
            }

            return $rueckgabewert;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
