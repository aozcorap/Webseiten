# Sommerfest 2026 – Anmeldung

Eigenständige, responsive Anmeldeseite (`index.html`) für das Vereins-Sommerfest
des Boxring Wetterau 1983 e.V. (05.09.2026, ab 16 Uhr), im Farb-/Font-Schema
der Vereinsseite ([boxring-wetterau.de](../boxring-wetterau.de)) – Inhalte
orientiert am offiziellen Flyer.

- Reines HTML/CSS/JS, kein Backend.
- Formular fragt Name, E-Mail, Anzahl Personen und Mitbringsel (Salat/Kuchen/Nichts) ab.
- Pfand: 5 € pro angemeldeter Person, wird live berechnet und angezeigt.
  Zahlung im Voraus per PayPal (E-Mail-Adresse, „Freunde & Familie“ zur
  Gebührenvermeidung) – **kein echtes Zahlungs-Backend**, es wird nur die
  PayPal-Adresse zum manuellen Überweisen angezeigt.
- Formular öffnet beim Absenden zusätzlich das E-Mail-Programm (`mailto:`)
  mit den eingegebenen Daten als Nachricht – es gibt keinen echten
  Formular-Endpunkt, die Anmeldung geht per E-Mail an ahmet@ozcorapci.de.
- TODO: Sobald ein echter PayPal.me-Link oder Vereinskonto existiert, den
  Hinweistext/Link in `index.html` (Bereich `.pfandbox`) austauschen.
