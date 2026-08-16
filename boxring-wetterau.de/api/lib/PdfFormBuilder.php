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


    /** @param array<string, mixed> $data */
    private static function fillPageOne(Fpdi $pdf, array $data): void
    {
        // x-Werte = exakte Spaltentrenner der Original-Vorlage (aus den
        // Tabellen-Fuellrechtecken vermessen) + 6pt Innenabstand.
        self::row($pdf, 131, 148.6, 161.0, 175, $data['name'] ?? null);
        self::row($pdf, 374, 148.6, 161.0, 164, $data['vorname'] ?? null);

        self::row($pdf, 131, 171.2, 183.5, 175, $data['strasse'] ?? null);
        self::row($pdf, 346, 171.2, 183.5, 55, $data['hausnummer'] ?? null);

        self::row($pdf, 131, 194.0, 206.3, 62, $data['plz'] ?? null);
        self::row($pdf, 315, 194.0, 206.3, 223, $data['ort'] ?? null);

        self::row($pdf, 131, 216.6, 228.9, 175, $data['beruf'] ?? null);
        self::row($pdf, 374, 216.6, 228.9, 164, $data['geburtstag'] ?? null);

        self::row($pdf, 131, 239.4, 251.7, 407, $data['telefon'] ?? null);
        self::row($pdf, 131, 262.2, 274.5, 407, $data['email'] ?? null);

        if (($data['bilder_einwilligung'] ?? '') === 'ja') {
            self::checkbox($pdf, 456.0, 305.0, 465.8, 314.9);
        }
        // Kuendigungsbedingungen gelesen ist im Online-Formular Pflicht -> immer angehakt.
        self::checkbox($pdf, 466.1, 374.6, 475.9, 384.5);

        $beitragKey = (string) ($data['beitrag'] ?? '');
        if ($beitragKey === 'erwachsene_150') {
            self::checkbox($pdf, 268.3, 465.8, 278.2, 475.7); // Aktive
        } elseif ($beitragKey === 'jugendlich_75') {
            self::checkbox($pdf, 487.7, 465.8, 497.5, 475.7); // Jugendliche
        }
        if (isset($data['anteiliger_beitrag']) && $data['anteiliger_beitrag'] !== null) {
            $pdf->SetFont('Helvetica', 'B', 9);
            $pdf->SetTextColor(20, 20, 20);
            $pdf->SetXY(70.8, 490.8);
            $pdf->Cell(400, 10, self::t(sprintf('Anteiliger Beitrag im Beitrittsjahr (Online-Anmeldung): %.2f Euro', $data['anteiliger_beitrag'])), 0, 0, 'L');
        }

        // Der Hinweis zur Aufnahmegebuehr (grauer Kasten, fett, zentriert,
        // "Die einmalige Aufnahmegebuehr von 20 EUR wird zusammen mit dem
        // ersten Mitgliedsbeitrag automatisch per SEPA-Lastschrift vom
        // unten angegeben Konto eingezogen.") ist seit der Vorlagen-
        // Ueberarbeitung 2026 fest im Template eingedruckt - kein Overlay
        // mehr noetig.

        $ortDatum = trim(($data['unterschrift_ort'] ?? '') . ', ' . ($data['unterschrift_datum'] ?? ''), ' ,');
        self::row($pdf, 75, 675.4, 687.8, 140, $ortDatum);
        self::row($pdf, 278, 675.4, 687.8, 250, $data['signatur_antrag'] ?? null);
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

        // Kontoinhaber-Tabelle (4 Zeilen: Name und Vorname / Strasse und Nr. /
        // PLZ und Ort / Bankverbindung) - Wertspalte beginnt bei x=179.0,
        // Zeilen-y aus der ueberarbeiteten Vorlage 2026 vermessen. Seit der
        // Ueberarbeitung gibt es keine separate "Bankverbindung"-Ueberschrift
        // und keinen "(Bitte ordentlich...)"-Hinweis mehr, und die IBAN wird
        // als normaler Text statt in einzelnen Kaestchen eingetragen (BIC
        // entfaellt komplett, s. Formular/Datenschutz).
        self::row($pdf, 185, 429.9, 442.3, 341, $kiName);
        self::row($pdf, 185, 453.0, 465.3, 341, $kiStrasse);
        self::row($pdf, 185, 476.2, 488.6, 341, $kiOrt);

        $iban = (string) ($data['iban'] ?? '');
        $ibanFormatted = trim((string) preg_replace('/(.{4})/', '$1 ', $iban));
        self::row($pdf, 185, 499.5, 511.9, 341, 'IBAN: ' . $ibanFormatted);

        $ortDatum = trim(($data['unterschrift_ort'] ?? '') . ', ' . ($data['unterschrift_datum'] ?? ''), ' ,');
        self::row($pdf, 75, 594.1, 606.4, 140, $ortDatum);
        self::row($pdf, 288, 594.1, 606.4, 240, $data['signatur_sepa'] ?? null);

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
