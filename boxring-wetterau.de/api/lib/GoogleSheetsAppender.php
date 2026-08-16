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

        // Apps-Script-Web-Apps antworten auf den POST zunaechst mit einem 302
        // auf eine script.googleusercontent.com-URL, die die eigentliche
        // JSON-Antwort enthaelt. Bewusst KEIN automatisches Redirect-Folgen
        // (CURLOPT_FOLLOWLOCATION) - das hat sich als unzuverlaessig gezeigt,
        // je nachdem wie der Client mit dem Methodenwechsel POST->GET beim
        // Redirect umgeht. Stattdessen die Location-Adresse selbst auslesen
        // und in einem zweiten, einfachen GET abrufen.
        $redirectUrl = $this->postAndGetRedirect($this->webAppUrl, $body);
        [$status, $response] = $this->httpGet($redirectUrl);

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

    private function postAndGetRedirect(string $url, string $body): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("Apps-Script-Request fehlgeschlagen: $error");
        }
        $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);

        if (!is_string($redirectUrl) || $redirectUrl === '') {
            throw new RuntimeException("Apps Script hat keine Weiterleitung geliefert - Antwort: $response");
        }

        return $redirectUrl;
    }

    /** @return array{0:int,1:string} */
    private function httpGet(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("Apps-Script-Redirect-Request fehlgeschlagen: $error");
        }
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [$status, (string) $response];
    }
}
