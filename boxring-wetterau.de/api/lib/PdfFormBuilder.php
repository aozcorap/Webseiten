<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';
require_once __DIR__ . '/../vendor/fpdi/src/autoload.php';
require_once __DIR__ . '/Beitrag.php';

use setasign\Fpdi\Fpdi;

/**
 * Traegt die Formulardaten direkt in das echte Vereinsformular ein
 * (api/templates/beitrittserklaerung.pdf, zwei Seiten: Aufnahmeantrag +
 * SEPA-Mandat) statt es nachzubauen. Die Original-PDF-Seiten werden per
 * FPDI als Hintergrund importiert, die Werte werden an exakt vermessenen
 * Koordinaten (in pt, aus dem Original-PDF extrahiert) darueber geschrieben.
 *
 * Aendert sich das Layout der Vorlage (templates/beitrittserklaerung.pdf),
 * muessen die Koordinaten unten neu vermessen werden.
 */
final class PdfFormBuilder
{
    private const TEMPLATE_PATH = __DIR__ . '/../templates/beitrittserklaerung.pdf';

    /** @param array<string, mixed> $data */
    public static function build(array $data): string
    {
        $pdf = new Fpdi('P', 'pt', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetTitle('Aufnahmeantrag Boxring Wetterau 1983 e.V.');
        $pdf->SetMargins(0, 0, 0);

        $pageCount = $pdf->setSourceFile(self::TEMPLATE_PATH);

        // FPDF schreibt Seiten sequenziell in den Ausgabestream - man kann
        // nach AddPage() nicht mehr auf eine vorherige Seite zurückspringen.
        // Deshalb: Vorlagenseite importieren, SOFORT die Felder dieser Seite
        // ueberschreiben, danach erst zur naechsten Seite weitergehen.
        $tplId = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tplId);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);
        self::fillPageOne($pdf, $data);

        if ($pageCount >= 2) {
            $tplId2 = $pdf->importPage(2);
            $size2 = $pdf->getTemplateSize($tplId2);
            $pdf->AddPage($size2['orientation'], [$size2['width'], $size2['height']]);
            $pdf->useTemplate($tplId2);
            self::fillPageTwo($pdf, $data);
        }

        return $pdf->Output('S');
    }

    private static function t(?string $text): string
    {
        return iconv('UTF-8', 'CP1252//TRANSLIT', $text ?? '');
    }

    /** Schreibt Text linksbuendig in eine Zeile, y0/y1 = Ober-/Unterkante der Zielzeile (aus dem Original-PDF vermessen). */
    private static function row(Fpdi $pdf, float $x, float $y0, float $y1, float $width, ?string $text, float $fontSize = 10): void
    {
        if ($text === null || $text === '') {
            return;
        }
        $pdf->SetFont('Helvetica', '', $fontSize);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetXY($x, $y0);
        $pdf->Cell($width, $y1 - $y0, self::t($text), 0, 0, 'L');
    }

    /** Setzt ein "X" in eine Checkbox an gemessenen Koordinaten. */
    private static function checkbox(Fpdi $pdf, float $x0, float $y0, float $x1, float $y1): void
    {
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY($x0, $y0);
        $pdf->Cell($x1 - $x0, $y1 - $y0, 'X', 0, 0, 'C');
    }

    /** Schreibt einzelne Zeichen in eine Reihe von Kaestchen (IBAN/BIC). @param array<int, array{0:float,1:float}> $boxes je [x0, x1] */
    private static function charBoxes(Fpdi $pdf, array $boxes, float $y0, float $y1, string $chars): void
    {
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        foreach ($boxes as $i => [$bx0, $bx1]) {
            $char = $chars[$i] ?? '';
            if ($char === '') {
                continue;
            }
            $pdf->SetXY($bx0, $y0);
            $pdf->Cell($bx1 - $bx0, $y1 - $y0, $char, 0, 0, 'C');
        }
    }

    /** @param array<string, mixed> $data */
    private static function fillPageOne(Fpdi $pdf, array $data): void
    {
        // x-Werte = exakte Spaltentrenner der Original-Vorlage (aus den
        // Tabellen-Fuellrechtecken vermessen) + 6pt Innenabstand.
        self::row($pdf, 131, 150.8, 163.1, 175, $data['name'] ?? null);
        self::row($pdf, 374, 150.8, 163.1, 164, $data['vorname'] ?? null);

        self::row($pdf, 131, 173.4, 185.7, 175, $data['strasse'] ?? null);
        self::row($pdf, 346, 173.4, 185.7, 55, $data['hausnummer'] ?? null);

        self::row($pdf, 131, 196.2, 208.5, 62, $data['plz'] ?? null);
        self::row($pdf, 315, 196.2, 208.5, 223, $data['ort'] ?? null);

        self::row($pdf, 131, 218.7, 231.1, 175, $data['beruf'] ?? null);
        self::row($pdf, 374, 218.7, 231.1, 164, $data['geburtstag'] ?? null);

        self::row($pdf, 131, 241.5, 253.9, 407, $data['telefon'] ?? null);
        self::row($pdf, 131, 264.3, 276.7, 407, $data['email'] ?? null);

        if (($data['bilder_einwilligung'] ?? '') === 'ja') {
            self::checkbox($pdf, 456.0, 307.2, 465.8, 317.0);
        }
        // Kuendigungsbedingungen gelesen ist im Online-Formular Pflicht -> immer angehakt.
        self::checkbox($pdf, 466.1, 376.8, 475.9, 386.6);

        $beitragKey = (string) ($data['beitrag'] ?? '');
        if ($beitragKey === 'erwachsene_150') {
            self::checkbox($pdf, 268.3, 468.2, 278.2, 478.1); // Aktive
        } elseif ($beitragKey === 'jugendlich_75') {
            self::checkbox($pdf, 487.7, 468.2, 497.5, 478.1); // Jugendliche
        }
        if (isset($data['anteiliger_beitrag']) && $data['anteiliger_beitrag'] !== null) {
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->SetXY(70.8, 493.0);
            $pdf->Cell(400, 10, self::t(sprintf('Anteiliger Beitrag im Beitrittsjahr (Online-Anmeldung): %.2f Euro', $data['anteiliger_beitrag'])), 0, 0, 'L');
        }

        $ortDatum = trim(($data['unterschrift_ort'] ?? '') . ', ' . ($data['unterschrift_datum'] ?? ''), ' ,');
        self::row($pdf, 75, 682, 692, 140, $ortDatum);
        self::row($pdf, 278, 682, 692, 250, $data['signatur_antrag'] ?? null);
    }

    /** @param array<string, mixed> $data */
    private static function fillPageTwo(Fpdi $pdf, array $data): void
    {
        $kontoGleich = ($data['kontoinhaber_gleich_antragsteller'] ?? '') === 'ja';
        $kiName = $kontoGleich
            ? trim(($data['vorname'] ?? '') . ' ' . ($data['name'] ?? ''))
            : ($data['kontoinhaber_name'] ?? null);
        $kiStrasse = $kontoGleich
            ? trim(($data['strasse'] ?? '') . ' ' . ($data['hausnummer'] ?? ''))
            : ($data['kontoinhaber_strasse'] ?? null);
        $kiOrt = $kontoGleich
            ? trim(($data['plz'] ?? '') . ' ' . ($data['ort'] ?? ''))
            : ($data['kontoinhaber_ort'] ?? null);

        self::row($pdf, 180, 459.2, 471.5, 350, $kiName);
        self::row($pdf, 180, 482.5, 494.8, 350, $kiStrasse);
        self::row($pdf, 180, 505.5, 517.9, 350, $kiOrt);

        // IBAN-Kaestchen: Box 0 = "D", Box 1 = "E" (im Original bereits vorgedruckt),
        // Boxen 2..21 nehmen die 20 Ziffern nach "DE" auf.
        $ibanBoxes = [
            [204.7, 217.9], [218.4, 232.1], [232.8, 246.5], [247.0, 260.2], [261.6, 274.8],
            [275.3, 289.0], [289.4, 303.4], [303.8, 317.0], [318.5, 331.7], [332.2, 345.8],
            [346.3, 360.0], [360.5, 373.7], [375.4, 388.6], [389.0, 402.7], [403.2, 416.9],
            [417.4, 430.6], [432.0, 445.2], [445.7, 459.4], [459.8, 473.8], [474.2, 487.4],
            [488.9, 502.1], [502.6, 516.0],
        ];
        $iban = (string) ($data['iban'] ?? '');
        $ibanRest = str_starts_with($iban, 'DE') ? substr($iban, 2) : $iban;
        self::charBoxes($pdf, array_slice($ibanBoxes, 2), 575.8, 589.9, $ibanRest);

        // BIC wird seit SEPA-Umstellung nicht mehr im Online-Formular abgefragt
        // (innerhalb der EU/EWR seit Februar 2016 nicht mehr Pflicht) - die
        // entsprechenden Kaestchen auf der Vorlage bleiben daher leer.

        $ortDatum = trim(($data['unterschrift_ort'] ?? '') . ', ' . ($data['unterschrift_datum'] ?? ''), ' ,');
        self::row($pdf, 75, 672, 682, 140, $ortDatum);
        self::row($pdf, 288, 672, 682, 240, $data['signatur_sepa'] ?? null);

        $pdf->SetFont('Helvetica', 'I', 7);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->SetXY(70.8, 715);
        $pdf->MultiCell(460, 10, self::t(
            'Online eingereicht am ' . ($data['eingereicht_am'] ?? '') . ' über boxring-wetterau.de. ' .
            'Die digitale Unterschrift ersetzt die handschriftliche Unterschrift und wurde durch aktive Eingabe ' .
            'des vollen Namens im Online-Formular bestätigt.'
        ));
    }
}
