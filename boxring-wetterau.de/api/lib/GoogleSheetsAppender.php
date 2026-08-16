<?php
declare(strict_types=1);

/**
 * Haengt eine Zeile ans Google Sheet "Boxring Wetterau – Mitgliederliste" an,
 * ueber einen einfachen Google Apps Script Web-App-Endpoint (siehe
 * api/apps-script/mitgliederliste.gs). Kein Google-Cloud-Projekt, keine
 * Service-Account-Datei noetig - nur ein simpler HTTP-POST mit Shared Secret.
 *
 * Die Mitgliedsnummer wird NICHT hier in PHP berechnet, sondern im Apps
 * Script selbst (das hat ohnehin lesenden Zugriff auf die aktuelle Spalte
 * und vermeidet ein Race Window zwischen Lesen und Schreiben).
 */
final class GoogleSheetsAppender
{
    public function __construct(
        private readonly string $webAppUrl,
        private readonly string $sharedSecret
    ) {
    }

    /**
     * @param array<int, scalar|null> $row
     * @return int die vom Apps Script vergebene Mitgliedsnummer
     */
    public function appendRow(array $row): int
    {
        $body = json_encode([
            'secret' => $this->sharedSecret,
            'row' => array_values($row),
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init($this->webAppUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            // Apps-Script-Web-Apps antworten mit einem 302 auf script.googleusercontent.com,
            // das die eigentliche JSON-Antwort enthaelt - muss verfolgt werden.
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("Apps-Script-Request fehlgeschlagen: $error");
        }
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException("Apps-Script-Request fehlgeschlagen (HTTP $status): $response");
        }

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data['success'])) {
            $message = is_array($data) ? ($data['message'] ?? 'unbekannter Fehler') : 'ungueltige Antwort';
            throw new RuntimeException("Apps Script meldet Fehler: $message (Antwort: $response)");
        }

        return (int) $data['mitgliedsnr'];
    }
}
