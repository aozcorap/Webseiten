<?php
declare(strict_types=1);

/** Einheitliche JSON-Antwort fuer die trainer-*.php-Endpunkte. */
final class JsonResponse
{
    public static function send(int $httpCode, array $payload): never
    {
        http_response_code($httpCode);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
