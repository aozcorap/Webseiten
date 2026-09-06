# Vereinsshop

Single-File-Shop (`index.html`), in dem Vereinsmitglieder Kleidung aus der
adidas-Entrada-26-Kollektion in ihrer Größe bestellen und bezahlen können.
Basiert auf dem Angebot TP-O-10003449 von eleven teamsports (Stand August
2026); die Entrada-22-Kollektion wurde ersetzt, da sie nicht mehr lieferbar
ist. Live unter `www.boxring-wetterau.de/shop/`, verlinkt im Hauptmenü der
Vereinsseite als "Vereinsshop". Reines Frontend ohne eigenes Backend — siehe
Zahlung/Admin unten für die Details, wie das ohne Server funktioniert.

## Konzept

- **Zugang**: Kein Login/Account — Link wird intern im Verein geteilt, Katalog
  ist direkt aufrufbar. Name wird nur beim Bezahlen für die Bestellzuordnung
  abgefragt.
- **Bestellung**: Katalog mit Größenauswahl (XS–XXL), Sammelbestell-Fenster
  statt laufender Einzelbestellungen.
- **Bezahlung**: Ausschließlich Banküberweisung (IBAN wird nach der Bestellung
  angezeigt). Läuft ohne Backend — die Seite selbst weiß nicht, ob wirklich
  gezahlt wurde; der Abgleich passiert manuell über den Kontoauszug, per
  Bestellnummer + Name als Verwendungszweck.
- **Bestell-Benachrichtigung**: Bei jeder abgeschlossenen Bestellung wird per
  Formspree (`ORDER_NOTIFY_ENDPOINT` in `index.html`) automatisch eine Mail
  an die Zeugwart:in geschickt, damit sie parallel zum Kontoauszug weiß, was
  reinkommen sollte.
- **Admin**: Bestellliste + CSV-Export für die Zeugwart:in.

## Produktbilder

Die Produktfotos in diesem Entwurf sind von 11teamsports.com bzw.
flyeralarm-sports.com eingebettet (Base64, damit sie auch offline/in
Sandboxes angezeigt werden). Für den produktiven Einsatz: Bild-URLs direkt
referenzieren statt einbetten, und vorher rechtlich klären, ob die Nutzung
der Fremdbilder für den Vereins-internen Shop okay ist.

## Preise

Die angezeigten Preise sind die UVP der jeweiligen Artikel (nicht der
Einkaufspreis, den der Verein bei eleven teamsports zahlt). Der Aufschlag
deckt u. a. die Kosten für die Wappen-Veredelung (5 € pro Artikel lt. Angebot).
