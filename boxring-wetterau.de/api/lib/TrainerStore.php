<?php
declare(strict_types=1);

require_once __DIR__ . '/JsonFileStore.php';

/**
 * Datenzugriff fuer die Trainer-Zeiterfassung: Trainer-Accounts,
 * Stundeneintraege und Abrechnungen. Speicherung als JSON-Dateien in
 * api/data/ (siehe JsonFileStore) statt einer eigenen Datenbank.
 */
final class TrainerStore
{
    private static function trainersPath(): string
    {
        return __DIR__ . '/../data/trainers.json';
    }

    private static function stundenPath(): string
    {
        return __DIR__ . '/../data/stunden.json';
    }

    private static function abrechnungenPath(): string
    {
        return __DIR__ . '/../data/abrechnungen.json';
    }

    // --- Trainer-Accounts ---------------------------------------------

    public static function findTrainerByEmail(string $email): ?array
    {
        $emailLower = mb_strtolower($email);
        return self::withTrainers(function (array $data) use ($emailLower) {
            foreach ($data['trainers'] as $trainer) {
                if (mb_strtolower($trainer['email']) === $emailLower) {
                    return [null, $trainer];
                }
            }
            return [null, null];
        });
    }

    public static function findTrainerById(int $id): ?array
    {
        return self::withTrainers(function (array $data) use ($id) {
            foreach ($data['trainers'] as $trainer) {
                if ($trainer['id'] === $id) {
                    return [null, $trainer];
                }
            }
            return [null, null];
        });
    }

    public static function findTrainerByApproveToken(string $token): ?array
    {
        return self::withTrainers(function (array $data) use ($token) {
            foreach ($data['trainers'] as $trainer) {
                if (!empty($trainer['approveToken']) && hash_equals($trainer['approveToken'], $token)) {
                    return [null, $trainer];
                }
            }
            return [null, null];
        });
    }

    /** @return array Der neu angelegte Trainer-Datensatz. */
    public static function createTrainer(string $vorname, string $nachname, string $email, string $passwordHash, string $approveToken, string $approveTokenExpiry): array
    {
        return self::withTrainers(function (array $data) use ($vorname, $nachname, $email, $passwordHash, $approveToken, $approveTokenExpiry) {
            $id = $data['nextId'];
            $trainer = [
                'id' => $id,
                'vorname' => $vorname,
                'nachname' => $nachname,
                'email' => $email,
                'passwordHash' => $passwordHash,
                'status' => 'pending', // pending | aktiv | abgelehnt
                'approveToken' => $approveToken,
                'approveTokenExpiry' => $approveTokenExpiry,
                'erstelltAm' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('c'),
            ];
            $data['trainers'][] = $trainer;
            $data['nextId'] = $id + 1;
            return [$data, $trainer];
        });
    }

    public static function setTrainerStatus(int $id, string $status): void
    {
        self::withTrainers(function (array $data) use ($id, $status) {
            foreach ($data['trainers'] as &$trainer) {
                if ($trainer['id'] === $id) {
                    $trainer['status'] = $status;
                    $trainer['approveToken'] = null;
                    $trainer['approveTokenExpiry'] = null;
                }
            }
            unset($trainer);
            return [$data, null];
        });
    }

    private static function withTrainers(callable $mutator): mixed
    {
        return JsonFileStore::withLock(self::trainersPath(), ['nextId' => 1, 'trainers' => []], $mutator);
    }

    // --- Stundeneintraege ------------------------------------------------

    /** @return array<int, array{trainerId:int,datum:string,stunden:int,aktualisiertAm:string}> */
    public static function stundenFuerMonat(int $trainerId, string $monat): array
    {
        return JsonFileStore::withLock(self::stundenPath(), ['eintraege' => []], function (array $data) use ($trainerId, $monat) {
            $treffer = array_values(array_filter($data['eintraege'], function ($eintrag) use ($trainerId, $monat) {
                return $eintrag['trainerId'] === $trainerId && str_starts_with($eintrag['datum'], $monat);
            }));
            return [null, $treffer];
        });
    }

    /** Setzt/aktualisiert die Stunden fuer einen Tag. $stunden === 0 loescht den Eintrag. */
    public static function stundenSpeichern(int $trainerId, string $datum, int $stunden): void
    {
        JsonFileStore::withLock(self::stundenPath(), ['eintraege' => []], function (array $data) use ($trainerId, $datum, $stunden) {
            $data['eintraege'] = array_values(array_filter($data['eintraege'], function ($eintrag) use ($trainerId, $datum) {
                return !($eintrag['trainerId'] === $trainerId && $eintrag['datum'] === $datum);
            }));
            if ($stunden > 0) {
                $data['eintraege'][] = [
                    'trainerId' => $trainerId,
                    'datum' => $datum,
                    'stunden' => $stunden,
                    'aktualisiertAm' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('c'),
                ];
            }
            return [$data, null];
        });
    }

    // --- Abrechnungen ------------------------------------------------

    public static function abrechnungFuerMonat(int $trainerId, string $monat): ?array
    {
        return JsonFileStore::withLock(self::abrechnungenPath(), ['abrechnungen' => []], function (array $data) use ($trainerId, $monat) {
            foreach ($data['abrechnungen'] as $abrechnung) {
                if ($abrechnung['trainerId'] === $trainerId && $abrechnung['monat'] === $monat) {
                    return [null, $abrechnung];
                }
            }
            return [null, null];
        });
    }

    /**
     * Legt eine Abrechnung an - schlaegt fehl (gibt false zurueck), falls
     * fuer diesen Trainer/Monat bereits abgerechnet wurde. So ist
     * ausgeschlossen, dass durch einen doppelten Klick zweimal fuer
     * denselben Monat abgerechnet wird ("wir zahlen nur 1x pro Monat").
     */
    public static function abrechnungAnlegen(int $trainerId, string $monat, int $stunden, float $betrag): bool
    {
        return JsonFileStore::withLock(self::abrechnungenPath(), ['abrechnungen' => []], function (array $data) use ($trainerId, $monat, $stunden, $betrag) {
            foreach ($data['abrechnungen'] as $abrechnung) {
                if ($abrechnung['trainerId'] === $trainerId && $abrechnung['monat'] === $monat) {
                    return [null, false];
                }
            }
            $data['abrechnungen'][] = [
                'trainerId' => $trainerId,
                'monat' => $monat,
                'stunden' => $stunden,
                'betrag' => $betrag,
                'abgerechnetAm' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('c'),
            ];
            return [$data, true];
        });
    }
}
