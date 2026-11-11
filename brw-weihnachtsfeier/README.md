# Weihnachtsfeier 2026 – Anmeldung

Live: https://aozcorap.github.io/Webseiten/brw-weihnachtsfeier/

Klon von `../brw-sommerfest/` (gleiches Konzept: Formular, 5 € Pfand pro Person
per PayPal oder bar, Admin-Overlay). Termin: Freitag, 11.12.2026, Berry Bowling
Center, Dieselstraße 1, 61169 Friedberg. Bowling 20:00–22:00 Uhr (Option A der
Berry-Mail), Burger-Buffet. Eigenes Erscheinungsbild (Rot/Grün statt Orange).

## Offen
- **Supabase:** Projekt `brw-weihnachtsfeier` (`oyvsbuynoyhawtzrutbs`, eu-central-1),
  Tabelle `anmeldungen`, Keepalive per pg_cron alle 5 Tage. Policies wie beim
  Sommerfest offen für den publishable key (Admin-Passwort nur clientseitig).
- Benachrichtigungsmail über `boxring-wetterau.de/api/weihnachtsfeiernotify.php` – muss auf boxring-wetterau.de deployt werden.
- Ankunft "ab 18:00 Uhr" – Berry nennt 19:00 als frühesten Einlass. Klären.
- Anfahrt Auto/ÖPNV fehlt (nicht erfunden).
- Admin-Zugang und offene DB-Policies wie beim Sommerfest (siehe dortiges README).
