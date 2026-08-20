<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

/**
 * Baut die monatliche PDF-Rechnung des Haupttrainers (HAUPTTRAINER_EMAIL in
 * config.php) nach dem Muster einer echten, bereits verschickten Rechnung
 * (202607_BRW) - eigenes einfaches Layout per FPDF, kein Formular-Overlay
 * noetig wie bei PdfFormBuilder.
 *
 * Der Haupttrainer ist (anders als die normalen Trainer) umsatzsteuerpflichtig:
 * TRAINER_STUNDENSATZ gilt bei ihm als Brutto-Stundensatz inkl. 19% MwSt.,
 * Netto/MwSt werden fuer die Rechnung daraus zurueckgerechnet.
 */
final class RechnungBuilder
{
    private const MWST_SATZ = 0.19;

    private static array $monatsnamen = [
        1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April', 5 => 'Mai', 6 => 'Juni',
        7 => 'Juli', 8 => 'August', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember',
    ];

    /**
     * @param array{monat:string,stunden:int,betragBrutto:float,rechnungsdatum:DateTimeImmutable} $daten
     */
    public static function build(array $daten): string
    {
        $monatTeile = explode('-', $daten['monat']);
        $monatName = self::$monatsnamen[(int) $monatTeile[1]];
        $jahr = $monatTeile[0];
        $rechnungsnummer = $daten['monat'][0] . $daten['monat'][1] . $daten['monat'][2] . $daten['monat'][3] . $monatTeile[1] . '_BRW';

        $betragBrutto = $daten['betragBrutto'];
        $betragNetto = round($betragBrutto / (1 + self::MWST_SATZ), 2);
        $mwstBetrag = round($betragBrutto - $betragNetto, 2);
        $stundensatzNetto = round($betragNetto / $daten['stunden'], 2);

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetTitle('Rechnung ' . $rechnungsnummer);
        $pdf->SetMargins(20, 20, 20);
        $pdf->AddPage();
        $pdf->SetTextColor(20, 20, 20);

        // --- Absenderblock oben rechts ---
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetXY(110, 20);
        $pdf->Cell(80, 5.5, self::t(HAUPTTRAINER_NAME), 0, 2, 'R');
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetX(110);
        $pdf->Cell(80, 5.5, self::t(HAUPTTRAINER_STRASSE), 0, 2, 'R');
        $pdf->SetX(110);
        $pdf->Cell(80, 5.5, self::t(HAUPTTRAINER_PLZ_ORT), 0, 2, 'R');

        // --- Ruecksendezeile (klein, unterstrichen) + Empfaengerblock ---
        $pdf->SetFont('Helvetica', 'U', 8);
        $pdf->SetXY(20, 55);
        $pdf->Cell(150, 4, self::t(HAUPTTRAINER_NAME . ', ' . HAUPTTRAINER_STRASSE . ', ' . HAUPTTRAINER_PLZ_ORT), 0, 2);

        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetX(20);
        $pdf->Cell(150, 5.5, self::t(VEREIN_RECHNUNGSNAME), 0, 2);
        $pdf->SetX(20);
        $pdf->Cell(150, 5.5, self::t(VEREIN_RECHNUNGSSTRASSE), 0, 2);
        $pdf->Ln(2);
        $pdf->SetX(20);
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(150, 5.5, self::t(VEREIN_RECHNUNGSORT), 0, 2);

        // --- Ort/Datum rechtsbuendig ---
        $datumText = HAUPTTRAINER_ORT . ', den ' . ltrim($daten['rechnungsdatum']->format('d'), '0') . '. ' . self::datumLangText($daten['rechnungsdatum']);
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->SetXY(110, 88);
        $pdf->Cell(80, 5.5, self::t($datumText), 0, 0, 'R');

        // --- Rechnungsnummer ---
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->SetXY(20, 100);
        $pdf->Cell(150, 6, self::t('Rechnung: ' . $rechnungsnummer), 0, 2);

        // --- Anrede + Einleitungstext ---
        $pdf->Ln(6);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetX(20);
        $pdf->Cell(170, 5.5, 'Sehr geehrte Damen und Herren,', 0, 2);
        $pdf->Ln(3);
        $pdf->SetX(20);
        $pdf->MultiCell(170, 5.5, self::t(
            'bezugnehmend auf unsere Vereinbarung sind folgende Trainerstunden zur Abrechnung fuer den Monat ' .
            $monatName . ' ' . $jahr . ' angefallen:'
        ));

        // --- Tabelle (Spaltenbreiten/Kopfzeilen an die echte Vorlage angelehnt) ---
        $tabelleY = $pdf->GetY() + 6;
        $spalten = [15, 65, 30, 30, 30]; // Position, Beschreibung, Geleistete Stunden, Stundensatz, Summe
        $koepfe = [['Position'], ['Beschreibung'], ['Geleistete', 'Stunden'], ['Stunden-', 'satz'], ['Summe']];
        $kopfHoehe = 12;

        $pdf->SetFillColor(222, 234, 250);
        $pdf->SetTextColor(30, 70, 150);
        $pdf->SetFont('Helvetica', 'B', 10);
        $x = 20;
        foreach ($koepfe as $i => $zeilen) {
            self::mehrzeiligeZelle($pdf, $x, $tabelleY, $spalten[$i], $kopfHoehe, $zeilen, $i === 1 ? 'L' : 'C', true);
            $x += $spalten[$i];
        }

        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(20, $tabelleY + $kopfHoehe);
        $pdf->Cell($spalten[0], 8, '1', 1, 0, 'C');
        $pdf->Cell($spalten[1], 8, 'Trainerstunden', 1, 0, 'L');
        $pdf->Cell($spalten[2], 8, (string) $daten['stunden'], 1, 0, 'C');
        self::geldZelle($pdf, $spalten[3], 8, self::geld($stundensatzNetto));
        self::geldZelle($pdf, $spalten[4], 8, self::geld($betragNetto));

        $summenBreite = $spalten[0] + $spalten[1] + $spalten[2] + $spalten[3];
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY(20, $tabelleY + $kopfHoehe + 8);
        $pdf->Cell($summenBreite, 8, 'Summe Netto', 1, 0, 'L');
        self::geldZelle($pdf, $spalten[4], 8, self::geld($betragNetto));

        $pdf->SetFont('Helvetica', '', 10);
        $pdf->SetXY(20, $tabelleY + $kopfHoehe + 16);
        $pdf->Cell($summenBreite, 8, 'MwSt. (' . round(self::MWST_SATZ * 100) . '%)', 1, 0, 'L');
        self::geldZelle($pdf, $spalten[4], 8, self::geld($mwstBetrag));

        $pdf->SetFillColor(222, 234, 250);
        $pdf->SetTextColor(30, 70, 150);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetXY(20, $tabelleY + $kopfHoehe + 24);
        $pdf->Cell($summenBreite, 8, 'Summe Brutto', 1, 0, 'L', true);
        self::geldZelle($pdf, $spalten[4], 8, self::geld($betragBrutto), true);

        // Aeusserer Rahmen der Gesamttabelle bewusst dicker als die duennen
        // inneren Trennlinien zwischen den Zellen - klassisches Tabellen-Layout.
        $pdf->SetLineWidth(0.6);
        $pdf->Rect(20, $tabelleY, array_sum($spalten), $kopfHoehe + 32, 'D');
        $pdf->SetLineWidth(0.2);

        // --- Abschlusstext ---
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetXY(20, $tabelleY + $kopfHoehe + 24 + 8 + 8);
        $pdf->MultiCell(170, 5.5, 'Ich bitte, den Rechnungsbetrag binnen 14 Tage auf das u.g. Konto zu ueberweisen.');
        $pdf->Ln(4);
        $pdf->SetX(20);
        $pdf->Cell(170, 5.5, 'Mit freundlichen Gruessen', 0, 2);
        $pdf->SetX(20);
        $pdf->Cell(170, 5.5, self::t(HAUPTTRAINER_NAME), 0, 2);
        $pdf->Ln(4);
        $pdf->SetX(20);
        $pdf->SetFont('Helvetica', 'I', 9);
        $pdf->SetTextColor(110, 110, 110);
        $pdf->Cell(170, 5, 'Diese Rechnung wurde elektronisch erstellt und ist ohne Unterschrift gueltig.', 0, 2);

        // --- Fusszeile: Bankverbindung + Steuernummer ---
        $pdf->SetXY(20, 265);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(20, 263, 190, 263);
        $pdf->SetTextColor(20, 20, 20);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(170, 5, 'Bankverbindung:', 0, 2);
        $pdf->SetX(20);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(170, 5, self::t(HAUPTTRAINER_BANKNAME . ' | IBAN: ' . HAUPTTRAINER_IBAN . ' | BIC: ' . HAUPTTRAINER_BIC), 0, 2);
        $pdf->Ln(2);
        $pdf->SetX(20);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(35, 5, 'Steuernummer:', 0, 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(135, 5, self::t(HAUPTTRAINER_STEUERNUMMER), 0, 2);

        return $pdf->Output('S');
    }

    private static function t(string $text): string
    {
        return iconv('UTF-8', 'CP1252//TRANSLIT', $text);
    }

    /** Betrag mit echtem Euro-Zeichen statt "EUR" (CP1252 kennt € auf Code 0x80, FPDF-Kernschriften stellen es korrekt dar). */
    private static function geld(float $betrag): string
    {
        return self::t(number_format($betrag, 2, ',', '.') . ' €');
    }

    /**
     * Tabellenzelle fuer rechtsbuendige Betraege mit mehr Innenabstand zum
     * rechten Rand als der FPDF-Standard (~1mm) - sonst kleben die Zahlen
     * fast am Rahmen. $cMargin ist in dieser FPDF-Version protected, daher
     * hier manuell: Rahmen/Fuellung mit voller Breite zeichnen, Text danach
     * in einer schmaleren, unsichtbaren Zelle rechtsbuendig platzieren.
     */
    private static function geldZelle(FPDF $pdf, float $w, float $h, string $text, bool $fuellen = false): void
    {
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->Cell($w, $h, '', 1, 0, '', $fuellen);
        $pdf->SetXY($x, $y);
        $pdf->Cell($w - 3, $h, $text, 0, 0, 'R');
        $pdf->SetXY($x + $w, $y);
    }

    /** Zeichnet eine Tabellenzelle mit mehreren, vertikal zentrierten Textzeilen (fuer zweizeilige Tabellenkoepfe). */
    private static function mehrzeiligeZelle(FPDF $pdf, float $x, float $y, float $w, float $h, array $zeilen, string $align, bool $fuellen): void
    {
        $pdf->Rect($x, $y, $w, $h, $fuellen ? 'DF' : 'D');
        $zeilenHoehe = 4.2;
        $startY = $y + ($h - count($zeilen) * $zeilenHoehe) / 2;
        foreach ($zeilen as $i => $zeile) {
            $pdf->SetXY($x, $startY + $i * $zeilenHoehe);
            $pdf->Cell($w, $zeilenHoehe, self::t($zeile), 0, 0, $align);
        }
    }

    private static function datumLangText(DateTimeImmutable $datum): string
    {
        return self::$monatsnamen[(int) $datum->format('n')] . ' ' . $datum->format('Y');
    }
}
