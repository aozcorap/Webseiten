# Offene Punkte – CC Dienstleistungen Redesign (Stand 2026-08-28, Ende Session)

Redesign-Entwurf liegt in `cc-dienstleistungen-friedberg.de/` (`index.html`,
`impressum.html`, `datenschutz.html`), live unter
https://aozcorap.github.io/Webseiten/cc-dienstleistungen-friedberg.de/ —
Pitch-Grundlage für das Kundengespräch, kein Produktivsystem. Details zu
Design, Bildrechten, SEO und Agenten-Review: siehe
[cc-dienstleistungen-friedberg.de/README.md](cc-dienstleistungen-friedberg.de/README.md).

## Offen

- [ ] **Kontaktformular** hat noch keinen echten Versand-Endpunkt. Aktuell
      zeigt der Button beim Absenden nur "Danke! (Demo)" – es wird keine
      E-Mail verschickt und nichts gespeichert. Für den echten Betrieb
      braucht es einen Versand-Endpunkt (z. B. Formspree oder ein
      Mailserver-Skript), der die Anfrage tatsächlich an den Kunden
      zustellt.
- [ ] **Datenschutzerklärung**: Hosting-Abschnitt beschreibt aktuell
      GitHub Pages (Entwurfsphase, mit sichtbarem `[TODO]`) – vor Go-Live
      auf den echten Webspace umschreiben. Formular-Abschnitt nach
      Anbindung eines echten Endpunkts ergänzen (inkl. möglichem
      Auftragsverarbeiter).
- [ ] **`noindex`-Meta-Tag** auf allen drei Seiten entfernen, sobald die
      Seite unter der echten Domain live geht. Verhindert aktuell, dass
      Google diesen Entwurf neben der bestehenden Live-Seite unter
      cc-dienstleistungen-friedberg.de als Duplicate Content indexiert.
- [ ] **Anwaltlicher Gegencheck** von Impressum und Datenschutzerklärung
      vor dem echten Go-Live. Der bereits durchgeführte Agenten-Review
      (Code, SEO, Recht/Datenschutz – siehe README) ist eine technische
      Plausibilitätsprüfung, keine Rechtsberatung.

## Geschlossen (Kunde hat entschieden)

- **Ungeprüfte Angaben** (Umkreis, Öffnungszeiten, Festpreis, eigenes Team) –
  vom Kunden bestätigt.
- **USt-IdNr. / Handwerkskammer-Eintragung im Impressum** – bewusst
  weggelassen, stand auf der alten Seite auch nicht drauf.
- **Logo nur als Pixelbild** – kein Thema, Vektordatei nicht nötig.

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
