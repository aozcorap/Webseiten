<?php
declare(strict_types=1);

/**
 * Haengt eine Zeile an ein Google Sheet an, ueber die Sheets API v4 (REST)
 * mit einem Service-Account (JWT Bearer Flow). Keine Composer-Abhaengigkeit -
 * JWT-Signierung passiert direkt mit der PHP OpenSSL-Extension.
 *
 * Setup (einmalig, durch den Nutzer):
 * 1. Google-Cloud-Projekt anlegen, "Google Sheets API" aktivieren.
 * 2. Service-Account anlegen, JSON-Schluessel herunterladen.
 * 3. Das Ziel-Sheet fuer die client_email aus dem JSON-Schluessel als
 *    "Bearbeiter" freigeben (Sheet teilen wie mit einer Person).
 * 4. JSON-Schluessel-Datei AUSSERHALB des Webroots ablegen (oder per
 *    .htaccess vor direktem Zugriff schuetzen) und Pfad in config.php eintragen.
 */
final class GoogleSheetsAppender
{
    private string $serviceAccountEmail;
    private string $privateKey;
    private string $sheetId;
    private string $range;

    public function __construct(string $serviceAccountJsonPath, string $sheetId, string $range)
    {
        if (!is_readable($serviceAccountJsonPath)) {
            throw new RuntimeException("Google Service-Account-Datei nicht lesbar: $serviceAccountJsonPath");
        }

        $json = json_decode((string) file_get_contents($serviceAccountJsonPath), true);
        if (!is_array($json) || empty($json['client_email']) || empty($json['private_key'])) {
            throw new RuntimeException('Google Service-Account-Datei ist ungueltig (client_email/private_key fehlen).');
        }

        $this->serviceAccountEmail = $json['client_email'];
        $this->privateKey = $json['private_key'];
        $this->sheetId = $sheetId;
        $this->range = $range;
    }

    /** @param array<int, scalar|null> $row */
    public function appendRow(array $row): void
    {
        $token = $this->fetchAccessToken();

        $url = sprintf(
            'https://sheets.googleapis.com/v4/spreadsheets/%s/values/%s:append?valueInputOption=USER_ENTERED&insertDataOption=INSERT_ROWS',
            rawurlencode($this->sheetId),
            rawurlencode($this->range)
        );

        $body = json_encode(['values' => [array_values($row)]], JSON_UNESCAPED_UNICODE);

        [$status, $response] = $this->httpJson('POST', $url, $body, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);

        if ($status < 200 || $status >= 300) {
            throw new RuntimeException("Google Sheets append fehlgeschlagen (HTTP $status): $response");
        }
    }

    private function fetchAccessToken(): string
    {
        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claims = [
            'iss' => $this->serviceAccountEmail,
            'scope' => 'https://www.googleapis.com/auth/spreadsheets',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwtUnsigned = self::base64url(json_encode($header)) . '.' . self::base64url(json_encode($claims));

        $signature = '';
        $ok = openssl_sign($jwtUnsigned, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        if (!$ok) {
            throw new RuntimeException('JWT-Signierung fuer Google-Service-Account fehlgeschlagen.');
        }

        $jwt = $jwtUnsigned . '.' . self::base64url($signature);

        $postFields = http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        [$status, $response] = $this->httpForm('https://oauth2.googleapis.com/token', $postFields);

        $data = json_decode($response, true);
        if ($status !== 200 || !is_array($data) || empty($data['access_token'])) {
            throw new RuntimeException("Google OAuth-Token-Abruf fehlgeschlagen (HTTP $status): $response");
        }

        return $data['access_token'];
    }

    private static function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /** @return array{0:int,1:string} */
    private function httpJson(string $method, string $url, string $body, array $headers): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("Google-Sheets-Request fehlgeschlagen: $error");
        }
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$status, (string) $response];
    }

    /** @return array{0:int,1:string} */
    private function httpForm(string $url, string $postFields): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException("Google-OAuth-Request fehlgeschlagen: $error");
        }
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [$status, (string) $response];
    }
}
