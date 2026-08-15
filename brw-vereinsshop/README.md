# Vereinsausstatter – Entwurf

Klickbarer Entwurf (`index.html`) für einen Mini-Shop, in dem Vereinsmitglieder
Kleidung aus der adidas-Entrada-22-Kollektion (schwarz) in ihrer Größe
bestellen und bezahlen können. Reines Frontend-Mockup, kein Backend, keine
echte Zahlungsanbindung.

## Konzept

- **Zugang**: Kein Login/Account — Link wird intern im Verein geteilt, Katalog
  ist direkt aufrufbar. Name wird nur beim Bezahlen für die Bestellzuordnung
  abgefragt.
- **Bestellung**: Katalog mit Größenauswahl (XS–XXL), Sammelbestell-Fenster
  statt laufender Einzelbestellungen.
- **Bezahlung**: PayPal Checkout, Bestellung wird erst nach Zahlungseingang
  bestätigt.
- **Admin**: Bestellliste + CSV-Export für die Zeugwart:in.

## Geplanter Stack (für die echte Umsetzung)

- Frontend: Next.js/React, responsive
- Backend: Node.js API + Postgres
- Zahlung: PayPal Checkout SDK
- Hosting: Vercel/Netlify

## Produktbilder

Die Produktfotos in diesem Entwurf sind von 11teamsports.com eingebettet
(Base64, damit sie auch offline/in Sandboxes angezeigt werden). Für den
produktiven Einsatz: Bild-URLs direkt referenzieren statt einbetten, und
vorher rechtlich klären, ob die Nutzung der Fremdbilder für den
Vereins-internen Shop okay ist.
