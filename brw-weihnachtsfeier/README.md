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
- Beginn "ab 18:30 Uhr" (von Ahmet vorgegeben) – Berry nannte 19:00 als frühesten Einlass. Klären.
- Anfahrt Auto/ÖPNV fehlt (nicht erfunden).
- Admin-Zugang und offene DB-Policies wie beim Sommerfest (siehe dortiges README).

## Zahlung
Teilnehmer wählen im Formular PayPal oder Bar (Spalte `zahlungswunsch`). Bei Bar öffnet sich kein PayPal-Tab. Der Pfand-Status (Offen/Bar/PayPal) wird weiterhin im Admin gepflegt.
Admin: Link „Admin-Login“ unten auf der Seite.
