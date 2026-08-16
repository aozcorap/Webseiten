<?php
declare(strict_types=1);

/**
 * Serverseitige Validierung. Muss unabhängig von der Client-Prüfung in
 * mitglied-werden.html funktionieren, da Client-Validierung umgangen werden kann.
 */
final class Validation
{
    public static function ibanValid(string $rawIban): bool
    {
        $iban = strtoupper(str_replace(' ', '', $rawIban));
        if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}$/', $iban)) {
            return false;
        }

        $rearranged = substr($iban, 4) . substr($iban, 0, 4);
        $numeric = '';
        foreach (str_split($rearranged) as $char) {
            if (ctype_digit($char)) {
                $numeric .= $char;
            } else {
                $numeric .= (string) (ord($char) - 55);
            }
        }

        // Mod-97-Berechnung in Bloecken, da die Zahl fuer intdiv/bcmath zu gross werden kann.
        $remainder = 0;
        foreach (str_split($numeric) as $digit) {
            $remainder = ($remainder * 10 + (int) $digit) % 97;
        }

        return $remainder === 1;
    }

    public static function bicValid(string $rawBic): bool
    {
        $bic = strtoupper(str_replace(' ', '', $rawBic));
        return (bool) preg_match('/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/', $bic);
    }

    public static function emailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function dateValid(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d !== false && $d->format('Y-m-d') === $date;
    }

    /** Schneidet Whitespace, wandelt leere Strings zu null. */
    public static function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }
}
