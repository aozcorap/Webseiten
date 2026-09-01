<?php
declare(strict_types=1);

/**
 * Minimaler SMTP-Client mit STARTTLS + AUTH LOGIN, ohne externe Abhaengigkeiten.
 * Wirft eine RuntimeException mit der letzten Serverantwort bei jedem Fehler.
 */
function smtp_send(array $config, string $empfaenger, string $betreff, string $text, string $replyTo): void
{
    $host = $config['host'];
    $port = $config['port'];

    $sock = @stream_socket_client(
        "tcp://{$host}:{$port}",
        $errno,
        $errstr,
        15
    );
    if ($sock === false) {
        throw new RuntimeException("Verbindung fehlgeschlagen: {$errstr} ({$errno})");
    }

    $lesen = static function () use ($sock): string {
        $antwort = '';
        while (($zeile = fgets($sock, 515)) !== false) {
            $antwort .= $zeile;
            if (isset($zeile[3]) && $zeile[3] === ' ') {
                break;
            }
        }
        return $antwort;
    };

    $senden = static function (string $befehl) use ($sock, $lesen): string {
        fwrite($sock, $befehl . "\r\n");
        return $lesen();
    };

    $pruefen = static function (string $antwort, string $schritt): void {
        $code = (int)substr($antwort, 0, 3);
        if ($code < 200 || $code >= 400) {
            throw new RuntimeException("SMTP-Fehler bei {$schritt}: {$antwort}");
        }
    };

    $pruefen($lesen(), 'Verbindung');
    $pruefen($senden('EHLO cc-dienstleistungen-friedberg.de'), 'EHLO');
    $pruefen($senden('STARTTLS'), 'STARTTLS');

    if (!stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
        throw new RuntimeException('TLS-Verschluesselung konnte nicht aufgebaut werden.');
    }

    $pruefen($senden('EHLO cc-dienstleistungen-friedberg.de'), 'EHLO nach TLS');
    $pruefen($senden('AUTH LOGIN'), 'AUTH LOGIN');
    $pruefen($senden(base64_encode($config['username'])), 'Benutzername');
    $pruefen($senden(base64_encode($config['password'])), 'Passwort');

    $pruefen($senden('MAIL FROM:<' . $config['from'] . '>'), 'MAIL FROM');
    $pruefen($senden('RCPT TO:<' . $empfaenger . '>'), 'RCPT TO');
    $pruefen($senden('DATA'), 'DATA');

    $betreffKodiert = '=?UTF-8?B?' . base64_encode($betreff) . '?=';
    $nachricht = "From: {$config['from_name']} <{$config['from']}>\r\n"
        . "To: <{$empfaenger}>\r\n"
        . "Reply-To: <{$replyTo}>\r\n"
        . "Subject: {$betreffKodiert}\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "\r\n"
        . chunk_split(base64_encode($text))
        . ".";

    $pruefen($senden($nachricht), 'Nachrichtentext');
    $senden('QUIT');
    fclose($sock);
}
