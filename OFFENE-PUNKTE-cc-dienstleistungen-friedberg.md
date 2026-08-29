# Offene Punkte – CC Dienstleistungen Redesign (Stand 2026-08-28, Ende Session)

Redesign-Entwurf liegt in `cc-dienstleistungen-friedberg.de/` (`index.html`,
`impressum.html`, `datenschutz.html`), live unter
https://aozcorap.github.io/Webseiten/cc-dienstleistungen-friedberg.de/ —
Pitch-Grundlage für das Kundengespräch, kein Produktivsystem. Details zu
Design, Bildrechten, SEO und Agenten-Review: siehe
[cc-dienstleistungen-friedberg.de/README.md](cc-dienstleistungen-friedberg.de/README.md).

## Offen

- [ ] **Ungeprüfte Angaben** im Entwurf mit dem Kunden gegenchecken:
      „Umkreis 40 km", Öffnungszeiten „Mo–Fr 7:00–17:00 Uhr",
      „Festpreis-Angebot", „eigenes Team, keine Subunternehmer". Dieselben
      Werte stecken auch im JSON-LD auf der Startseite. (Gründungsjahr und
      Jahre im Handwerk sind vom Kunden bereits bestätigt, siehe Entscheidung
      unten.)
- [ ] **Kontaktformular** hat noch keinen echten Versand-Endpunkt (aktuell
      nur clientseitige Demo-Anzeige beim Absenden, es werden keine Daten
      übertragen oder gespeichert).
- [ ] **Impressum**: USt-IdNr. fehlt (eine früher öffentlich gezeigte
      Steuernummer wurde bewusst nicht übernommen). Handwerkskammer +
      Eintragungsnummer in der Handwerksrolle fehlen – Pflichtangabe, da
      Malerhandwerk zulassungspflichtig ist (Anlage A HwO).
- [ ] **Datenschutzerklärung**: Hosting-Abschnitt beschreibt aktuell
      GitHub Pages (Entwurfsphase) – vor Go-Live auf den echten Webspace
      umschreiben. Formular-Abschnitt nach Anbindung eines echten
      Endpunkts ergänzen (inkl. möglichem Auftragsverarbeiter).
- [ ] **`noindex`-Meta-Tag** auf allen drei Seiten entfernen, sobald die
      Seite unter der echten Domain live geht (aktuell bewusst gesetzt,
      um Duplicate-Content-Konflikte mit der bestehenden Live-Seite unter
      cc-dienstleistungen-friedberg.de zu vermeiden).
- [ ] **Logo** liegt nur als Pixelbild vor (aus dem vom Kunden gelieferten
      PDF extrahiert), keine Vektordatei. Für Großformat-Anwendungen
      (Fahrzeugbeschriftung, Bauschild) müsste beim Grafiker eine SVG-
      oder EPS-Datei angefragt werden.
- [ ] **Anwaltlicher Gegencheck** von Impressum und Datenschutzerklärung
      vor dem echten Go-Live. Der bereits durchgeführte Agenten-Review
      (Code, SEO, Recht/Datenschutz – siehe README) ist eine technische
      Plausibilitätsprüfung, keine Rechtsberatung.

## Entscheidung

- **Bildmaterial bleibt Pexels**: Der Kunde hat entschieden, die lizenzfreien
  Pexels-Platzhalterfotos dauerhaft zu übernehmen, keine eigenen Fotos.
  Bildrechte-Doku in `cc-dienstleistungen-friedberg.de/README.md` bleibt
  gültig (kommerzielle Nutzung erlaubt, kein Model Release nötig, da keine
  erkennbaren Personen).
- **Gründungsjahr korrigiert**: 2011 statt 2012 (Kundenangabe), „Jahre im
  Handwerk" entsprechend von 12+ auf 15+ angehoben. Betrifft Hero-Badge,
  About-Sektion und JSON-LD auf der Startseite.
- **Festnetznummer entfernt**: Der Kunde nutzt nur noch die Handynummer
  (0152 336 805 42). Alle Anruf-Links, Telefonanzeigen und der
  JSON-LD-Eintrag auf allen drei Seiten zeigen jetzt ausschließlich diese
  Nummer; die Festnetznummer 06031 160 90 98 kommt auf der Seite nicht mehr
  vor (auch nicht im Impressum).
- **E-Mail-Adresse geändert**: Kontakt läuft jetzt über `serkan@gmail.info`
  statt `info@cc-dienstleistungen-friedberg.de` (Formular-Anzeige, Footer,
  Topbar, Impressum, Datenschutzerklärung, JSON-LD).
- **Einwilligungs-Checkbox im Kontaktformular** ergänzt: Pflicht-Checkbox
  „Ich habe die Datenschutzerklärung gelesen und stimme der Verarbeitung
  meiner Angaben zur Bearbeitung dieser Anfrage zu" mit Link auf
  `datenschutz.html`. Datenschutzerklärung entsprechend um Art. 6 Abs. 1
  lit. a DSGVO als zusätzliche Rechtsgrundlage ergänzt.

## Bereits erledigt (diese Session)

- Layout- und Lesbarkeits-Fixes (Logo-Größe, Header-Anker-Sprünge, CTA-
  Konsistenz, Galerie-Seitenverhältnis, Schriftgrößen, fehlendes
  viewport-Meta-Tag)
- Firmenlogo korrekt aus dem Kunden-PDF extrahiert und eingebunden
  (CMYK-JPEG-Farbfehler dabei behoben)
- Bildmaterial mit erkennbaren Personen ausgetauscht (Model-Release-Risiko)
- Kontaktbereich überarbeitet: E-Mail-Feld im Formular ergänzt (fehlte),
  generische Kontaktkarten durch Nutzenaussagen ersetzt
- SEO-Basics (Meta-Tags, Open Graph, JSON-LD, Favicon, `noindex` für die
  Entwurfsphase)
- Schriften selbst gehostet statt über Google Fonts geladen (Abmahnrisiko
  durch IP-Übertragung an Google) – Ergebnis: 0 externe Netzwerk-Anfragen
  auf allen drei Seiten, kein Cookie-Banner nötig
- Impressum und Datenschutzerklärung als echte Unterseiten gebaut
- Drei-Agenten-Review durchgeführt (Code-QA, SEO-Technik, Recht/Daten-
  schutz), alle realen Befunde behoben
