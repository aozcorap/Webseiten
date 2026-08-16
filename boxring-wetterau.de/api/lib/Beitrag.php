<?php
declare(strict_types=1);

/**
 * Beitragsberechnung nach dem Modell aus der Beitrittserklaerung:
 * Jaehrlicher Beitrag faellig am 01.03. Bei Beitritt nach dem 01.03. wird
 * der Beitrag anteilig fuer die restlichen Tage des Jahres berechnet.
 */
final class Beitrag
{
    private const BETRAEGE = [
        'erwachsene_150' => ['label' => 'Erwachsene (ab 18 Jahre)', 'betrag' => 150.0],
        'jugendlich_75' => ['label' => 'Jugendliche (bis 18 Jahre)', 'betrag' => 75.0],
    ];

    public static function label(string $key): ?string
    {
        return self::BETRAEGE[$key]['label'] ?? null;
    }

    public static function jahresbetrag(string $key): ?float
    {
        return self::BETRAEGE[$key]['betrag'] ?? null;
    }

    /**
     * Beitragsart serverseitig aus dem Geburtsdatum ableiten (nie dem Client
     * vertrauen - das Formular schickt zwar bereits einen berechneten Wert
     * mit, der wird hier aber verbindlich neu bestimmt). Stichtag ist das
     * Beitrittsdatum.
     */
    public static function ausAlter(DateTimeImmutable $geburtstag, DateTimeImmutable $stichtag): string
    {
        $alter = $stichtag->diff($geburtstag)->y;
        return $alter < 18 ? 'jugendlich_75' : 'erwachsene_150';
    }

    /**
     * Anteiliger Beitrag im Beitrittsjahr. Beitritt bis einschliesslich 01.03.
     * -> voller Jahresbeitrag. Danach: (Resttage inkl. Beitrittstag / Tage im Jahr) * Jahresbeitrag.
     */
    public static function anteiligerBeitrag(string $key, DateTimeImmutable $beitrittsdatum): ?float
    {
        $jahresbetrag = self::jahresbetrag($key);
        if ($jahresbetrag === null) {
            return null;
        }

        $jahr = (int) $beitrittsdatum->format('Y');
        $stichtag = new DateTimeImmutable("$jahr-03-01");
        if ($beitrittsdatum <= $stichtag) {
            return round($jahresbetrag, 2);
        }

        $jahresende = new DateTimeImmutable("$jahr-12-31");
        $tageImJahr = (int) $beitrittsdatum->format('L') === 1 ? 366 : 365;
        $resttage = (int) $beitrittsdatum->diff($jahresende)->days + 1;

        return round(($resttage / $tageImJahr) * $jahresbetrag, 2);
    }
}
