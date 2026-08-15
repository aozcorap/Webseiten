<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

/**
 * Baut das ausgefuellte Aufnahme-Antrag- + SEPA-Mandat-PDF nach, das die
 * Person online eingereicht hat - inhaltlich analog zur Papier-Beitrittserklaerung.
 * FPDF liefert nur die Standard-Latin1-Fonts, daher Konvertierung von UTF-8.
 */
final class PdfFormBuilder
{
    /** @param array<string, mixed> $data */
    public static function build(array $data): string
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(18, 16, 18);
        $pdf->SetAutoPageBreak(true, 16);
        $pdf->SetTitle('Aufnahmeantrag Boxring Wetterau 1983 e.V.');

        self::pageOne($pdf, $data);
        self::pageTwo($pdf, $data);

        return $pdf->Output('S');
    }

    private static function t(?string $text): string
    {
        return iconv('UTF-8', 'CP1252//TRANSLIT', $text ?? '');
    }

    private static function heading(FPDF $pdf, string $text): void
    {
        $pdf->SetFillColor(232, 57, 79);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Helvetica', 'B', 14);
        $pdf->Cell(0, 10, self::t($text), 0, 1, 'C', true);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(4);
    }

    private static function field(FPDF $pdf, string $label, ?string $value, float $labelWidth = 45): void
    {
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell($labelWidth, 7, self::t($label), 0, 0);
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 7, self::t($value ?: '-'), 0, 1);
    }

    private static function checkboxLine(FPDF $pdf, bool $checked, string $text): void
    {
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(8, 6, $checked ? '[X]' : '[ ]', 0, 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 5, self::t($text));
        $pdf->Ln(1);
    }

    /** @param array<string, mixed> $data */
    private static function pageOne(FPDF $pdf, array $data): void
    {
        $pdf->AddPage();

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 5, self::t(
            "1. Vorsitzender: Ahmet Özcorapci, Hospitalgasse 36d, 61169 Friedberg\n" .
            "2. Vorsitzender: Dennis Stork, Mühlgasse 38, 35519 Rockenberg\n" .
            "Vereinskassierer: Leif Halmbohm, Waitz-von-Eschen-Str 17, 61231 Bad Nauheim\n" .
            "Bankverbindung: Volksbank Mittelhessen eG · BLZ 513 900 00 · Kto.-Nr. 87 359 808 · BIC VBMHDE5F · IBAN DE28 5139 0000 0087 3598 08"
        ));
        $pdf->Ln(4);

        self::heading($pdf, 'AUFNAHME-ANTRAG');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 5, self::t(
            'Hiermit erklärt die unten genannte Person ihren Beitritt zum Boxring Wetterau 1983 e.V. und erkennt ' .
            'damit gleichzeitig die Vereinssatzung und die Satzung des Hessischen Boxsport Verbandes (HBV) an. ' .
            'Online eingereicht über boxring-wetterau.de.'
        ));
        $pdf->Ln(3);

        self::field($pdf, 'Name:', $data['name'] ?? null);
        self::field($pdf, 'Vorname:', $data['vorname'] ?? null);
        self::field($pdf, 'Strasse, Nr.:', trim(($data['strasse'] ?? '') . ' ' . ($data['hausnummer'] ?? '')));
        self::field($pdf, 'PLZ, Ort:', trim(($data['plz'] ?? '') . ' ' . ($data['ort'] ?? '')));
        self::field($pdf, 'Beruf:', $data['beruf'] ?? null);
        self::field($pdf, 'Geburtstag:', $data['geburtstag'] ?? null);
        self::field($pdf, 'Telefon:', $data['telefon'] ?? null);
        self::field($pdf, 'E-Mail:', $data['email'] ?? null);
        if (!empty($data['erziehungsberechtigter'])) {
            self::field($pdf, 'Erziehungsberechtigte/r:', $data['erziehungsberechtigter']);
        }
        $pdf->Ln(2);

        self::checkboxLine(
            $pdf,
            ($data['bilder_einwilligung'] ?? '') === 'ja',
            'Einwilligung zur Veröffentlichung von Bildaufnahmen auf Webseite und/oder sozialen Medien des Vereins gemäß DSGVO.'
        );
        self::checkboxLine(
            $pdf,
            true,
            'Kündigungsbedingungen gelesen: Kündigung nur zum 31.12., schriftliche Austrittserklärung spätestens drei Monate zuvor an Kassenwart@boxring-wetterau.de.'
        );
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(0, 7, self::t('Jährlicher Mitgliedsbeitrag (fällig am 01.03.):'), 0, 1);
        $beitragKey = (string) ($data['beitrag'] ?? '');
        foreach ([
            'aktive_150' => 'Erwachsene – Aktive (150,- Euro)',
            'passive_30' => 'Erwachsene – Passive (30,- Euro)',
            'jugend_75' => 'Jugendliche bis 18 Jahre (75,- Euro)',
        ] as $key => $label) {
            self::checkboxLine($pdf, $beitragKey === $key, $label);
        }
        if (isset($data['anteiliger_beitrag']) && $data['anteiliger_beitrag'] !== null) {
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->Cell(0, 7, self::t(sprintf('Anteiliger Beitrag im Beitrittsjahr: %.2f Euro', $data['anteiliger_beitrag'])), 0, 1);
        }
        $pdf->Ln(1);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 5, self::t(
            'Es wird eine Aufnahmegebühr von 20,- Euro erhoben. Diese ist beim Vereinskassierer oder 1. Vorsitzenden ' .
            'direkt in bar zu entrichten. Für die Teilnahme an Kämpfen des HBV bzw. DBV sind Kampfpass und Lizenzmarke ' .
            'auf eigene Kosten zu beantragen; nötige ärztliche Atteste sind selbst zu besorgen.'
        ));
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(95, 7, self::t('Ort/Datum: ' . ($data['unterschrift_ort'] ?? '-') . ', ' . ($data['unterschrift_datum'] ?? '-')), 0, 0);
        $pdf->Cell(0, 7, self::t('Unterschrift (digital): ' . ($data['signatur_antrag'] ?? '-')), 0, 1);
    }

    /** @param array<string, mixed> $data */
    private static function pageTwo(FPDF $pdf, array $data): void
    {
        $pdf->AddPage();
        self::heading($pdf, 'SEPA-LASTSCHRIFTMANDAT');

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 5, self::t(
            'Zahlungsempfänger (Mandats-Gläubiger): Boxring Wetterau Friedberg 1983 e.V., Hospitalgasse 36d, 61169 Friedberg. ' .
            'Gläubiger-Identifikationsnummer: DE28ZZZ00001372209.'
        ));
        $pdf->Ln(3);

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

        self::field($pdf, 'Name, Vorname:', $kiName, 55);
        self::field($pdf, 'Strasse, Nr.:', $kiStrasse, 55);
        self::field($pdf, 'PLZ, Ort:', $kiOrt, 55);
        self::field($pdf, 'IBAN:', $data['iban'] ?? null, 55);
        self::field($pdf, 'BIC/SWIFT:', $data['bic'] ?? null, 55);
        $pdf->Ln(3);

        $pdf->SetFont('Helvetica', '', 9);
        $pdf->MultiCell(0, 5, self::t(
            'Hiermit wird der Vereinskassierer des Boxring Wetterau Friedberg 1983 e.V. ermächtigt, den jährlichen ' .
            'Mitgliedsbeitrag von obigem Konto einzuziehen. Diese Einzugsermächtigung kann jederzeit formlos widerrufen ' .
            'werden. Erstattung des belasteten Betrages kann innerhalb von 8 Wochen ab Belastungsdatum verlangt werden; ' .
            'es gelten die mit dem Kreditinstitut vereinbarten Bedingungen.'
        ));
        $pdf->Ln(6);

        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(95, 7, self::t('Ort/Datum: ' . ($data['unterschrift_ort'] ?? '-') . ', ' . ($data['unterschrift_datum'] ?? '-')), 0, 0);
        $pdf->Cell(0, 7, self::t('Unterschrift Kontoinhaber (digital): ' . ($data['signatur_sepa'] ?? '-')), 0, 1);

        $pdf->Ln(10);
        $pdf->SetFont('Helvetica', 'I', 8);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->MultiCell(0, 4, self::t(
            'Dieses Dokument wurde am ' . ($data['eingereicht_am'] ?? '') . ' über das Online-Formular auf ' .
            'boxring-wetterau.de eingereicht. Die digitale Unterschrift ersetzt die handschriftliche Unterschrift ' .
            'und wurde durch aktive Eingabe des vollen Namens im Formular bestätigt.'
        ));
    }
}
