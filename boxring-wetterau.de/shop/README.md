# Vereinsshop

Shop (`index.html` fürs Frontend + `api/bestellung.php`, `api/bestellungen.php`
fürs Backend), in dem Vereinsmitglieder Kleidung aus der adidas-Entrada-26-
Kollektion in ihrer Größe bestellen und bezahlen können. Basiert auf dem
Angebot TP-O-10003449 von eleven teamsports (Stand August 2026); die
Entrada-22-Kollektion wurde ersetzt, da sie nicht mehr lieferbar ist. Live
unter `www.boxring-wetterau.de/shop/`, verlinkt im Hauptmenü der Vereinsseite
als "Vereinsshop". Zeitlich befristete Aktion (Sammelbestell-Fenster von
wenigen Wochen), kein dauerhafter Online-Shop.

Setup-Anleitung für die benötigten `config.php`-Werte: siehe `api/SETUP.md`,
Abschnitt 9.

## Konzept

- **Zugang**: Kein Login/Account für Kunden — Link wird intern im Verein
  geteilt, Katalog ist direkt aufrufbar.
- **Bestellung**: Katalog mit Größenauswahl (XS–XXL, Kindergrößen 116–176),
  Sammelbestell-Fenster statt laufender Einzelbestellungen. Beim Checkout
  werden Name und E-Mail-Adresse abgefragt.
- **Bezahlung**: Banküberweisung oder bar im Training bei Ahmet Özcorapci.
  Kein PayPal (Betrag lässt sich dort nicht zuverlässig ohne Gebührenabzug
  garantieren, siehe Chat-Verlauf). Sowohl auf dem Bestätigungsbildschirm als
  auch in der Bestellmail werden **immer beide Zahlungswege** genannt,
  unabhängig von der im Checkout gewählten Präferenz — einige Mitglieder
  entscheiden sich nachträglich für den jeweils anderen Weg.
- **Bestellbestätigung per Mail**: `api/bestellung.php` verschickt nach jeder
  Bestellung eine Mail ans Mitglied (Absender `SHOP_NOTIFY_EMAIL`, CC an
  dieselbe Adresse), mit Bestellnummer, Artikeln, Summe und beiden
  Zahlungswegen. Die Bestellung wird VOR dem Mailversand gespeichert -
  schlägt die Mail fehl (z. B. SMTP-Aussetzer), bleibt die Bestellung trotzdem
  im Adminbereich sichtbar und geht nicht verloren.
- **Speicherung**: Bestellungen liegen dauerhaft in `api/data/orders.json`
  (Datei-Locking über `JsonFileStore`, gegen gleichzeitige Bestellungen
  abgesichert). Bestellnummern werden serverseitig fortlaufend vergeben
  (`OrdersStore`) - eine clientseitige Nummer wäre pro Browser-Tab bei 1000
  gestartet und hätte sich zwischen verschiedenen Kunden garantiert
  wiederholt.
- **Admin-Bereich** (`shop/index.html#admin`): eigener, echter serverseitiger
  Login (`api/shop-login.php` + `api/lib/ShopAdminSession.php`,
  Zugangsdaten `SHOP_ADMIN_USERNAME`/`SHOP_ADMIN_PASSWORD` in `config.php`) -
  bewusst getrennt vom Trainer-Zugang (`ADMIN_USERNAME`/`ADMIN_PASSWORD`),
  da hier echte Kunden-E-Mail-Adressen sichtbar sind. Funktionen:
  - Alle Bestellungen einsehen (Artikel, Größe, Farbe, Logo ja/nein, Preis).
  - Zahlungsart frei wählen und jederzeit korrigieren ("Bezahlt:
    Überweisung"/"Bezahlt: Bar") — unabhängig davon, was der Kunde beim
    Checkout angegeben hat.
  - Bestellungen löschen.
  - CSV-Export für den Reseller (eleven teamsports): aggregiert nach
    Artikel/Größe/Farbe/Logo mit Stückzahl, nicht nach Kunde.

## Preise

Die angezeigten Preise sind die UVP der jeweiligen Artikel (nicht der
Einkaufspreis, den der Verein bei eleven teamsports zahlt). Der Aufschlag
deckt u. a. die Kosten für die Wappen-Veredelung (5 € pro Artikel lt.
Angebot). Ob ein Artikel das Wappen bekommt, ergibt sich automatisch aus der
Produktdefinition in `index.html` (Feld `crest`) - keine manuelle Auswahl
durchs Mitglied nötig, manche Artikel (z. B. die Trainingshose) haben schlicht
kein Wappen im Angebot.

Preise sind clientseitig im `PRODUCTS`-Array hinterlegt und daher über die
Browser-Konsole theoretisch manipulierbar. Für dieses kurzlebige,
vereinsinterne Angebot bewusst in Kauf genommen statt eine serverseitige
Preisprüfung zu bauen.

## Produktbilder

Die Produktfotos in diesem Entwurf sind von 11teamsports.com bzw.
flyeralarm-sports.com eingebettet (Base64, damit sie auch offline/in
Sandboxes angezeigt werden). Für den produktiven Einsatz: Bild-URLs direkt
referenzieren statt einbetten, und vorher rechtlich klären, ob die Nutzung
der Fremdbilder für den Vereins-internen Shop okay ist.

## Wichtig beim Deployment (ZIP-Upload über Plesk)

`api/data/orders.json` und `api/config.php` liegen nur auf dem Server, nicht
im Repo (siehe `.gitignore`). Beim Hochladen einer neuen Version darauf
achten, dass der Upload-Prozess bestehende Dateien überschreibt statt den
Zielordner vorher zu leeren - sonst gehen alle bis dahin gesammelten
Bestellungen verloren.
