# Offene Punkte – CC Dienstleistungen Redesign (Stand 2026-09-01, Go-Live erfolgt)

Seite ist **live** unter https://cc-dienstleistungen-friedberg.de/ (IONOS-
Webspace). Details zu Design, Bildrechten, SEO und Agenten-Review: siehe
[cc-dienstleistungen-friedberg.de/README.md](cc-dienstleistungen-friedberg.de/README.md).

## Offen

- [ ] **Anwaltlicher Gegencheck** von Impressum und Datenschutzerklärung.
      Der bereits durchgeführte Agenten-Review (Code, SEO, Recht/Daten-
      schutz – siehe README) ist eine technische Plausibilitätsprüfung,
      keine Rechtsberatung. **Risiko-Entscheidung des Kunden**: Seite geht
      ohne diesen Check live, Risiko wird bewusst in Kauf genommen.
- [ ] **Aufräumen auf dem Webspace**: `mail-debug.log` (Debug-Ausgabe beim
      Testen des Kontaktformulars) und das SMTP-Testpasswort ändern, da es
      im Chatverlauf mit Claude stand.

## Erledigt beim Go-Live (2026-09-01)

- Kontaktformular an echten Versand angebunden: eigener minimaler
  SMTP-Client (`smtpmailer.php`) gegen `smtp.ionos.de` mit Login über das
  Postfach `info@cc-dienstleistungen-friedberg.de`. Zugangsdaten liegen in
  `smtpconfig.php`, bewusst nicht im Git-Repo (siehe `.gitignore`), nur
  manuell auf dem Webspace.
- Kontakt-E-Mail-Adresse korrigiert: `serkan@gmail.info` war ein
  Tippfehler (Domain existiert nicht, kein MX-Record) – jetzt überall
  `info@cc-dienstleistungen-friedberg.de`.
- Datenschutzerklärung: Hosting-Abschnitt beschreibt jetzt IONOS statt
  GitHub Pages, Formular-Abschnitt auf den echten SMTP-Versand angepasst.
- `noindex`-Meta-Tag von allen drei Seiten entfernt.
- Alte Website auf dem Webspace gesichert (Zip-Backup) und durch den
  Redesign-Entwurf ersetzt.

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
- **E-Mail-Adresse korrigiert**: Zwischenzeitlich stand überall
  `serkan@gmail.info` (Formular-Anzeige, Footer, Topbar, Impressum,
  Datenschutzerklärung, JSON-LD) — das war ein Tippfehler, die Domain
  `gmail.info` existiert nicht (kein MX-Record), Mails dorthin wären
  unzustellbar gewesen. Korrigiert auf das echte Postfach
  `info@cc-dienstleistungen-friedberg.de`.
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
