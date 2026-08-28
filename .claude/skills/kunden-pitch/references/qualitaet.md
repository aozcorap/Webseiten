# Qualitaets-Review

Durchzugehen, bevor ein Entwurf den Kunden sieht. Die Seite muss dabei
gerendert vor dir liegen - im Code sieht man diese Dinge nicht.

## Kill-Kriterien

Jeder einzelne Punkt hier laesst den Entwurf nach Platzhalter aussehen.
Einer reicht. Treffer werden behoben, nicht notiert.

**Erfundener Text.** Lorem ipsum, "Ihr zuverlaessiger Partner seit vielen
Jahren", ausgedachte Kundenstimmen, erfundene Zahlen. Der Kunde erkennt
seinen eigenen Betrieb nicht wieder und merkt in derselben Sekunde, dass
hier eine Vorlage befuellt wurde.

**Icon-Kacheln statt Fotos.** Drei bis sechs gleich grosse Karten mit
Piktogramm, Ueberschrift, zwei Zeilen Text. Das ist das deutlichste
KI-Erkennungsmerkmal ueberhaupt. Bei Handwerk sind Fotos der eigentliche
Verkaufsinhalt - ein Foto der verlegten Treppe schlaegt jedes Werkzeug-Icon.

**Stockfotos.** Laechelnde Models mit Schutzhelm in einem Buero. Wirkt
sofort unecht. Lieber ein technisch mittelmaessiges Originalfoto vom Kunden
als ein perfektes Stockbild.

**Gleichfoermigkeit.** Jede Sektion gleich hoch, gleicher Aufbau,
Ueberschrift mittig, Text darunter. Ohne Rhythmus wandert das Auge nicht,
und die Seite wirkt wie eine Liste statt wie ein Auftritt. Grosse Sektionen
mit kleinen abwechseln, volle Breite mit eingerueckten.

**Zu duenn.** Unter sechs Sektionen wirkt es wie eine Demo. Eine echte
Website hat Tiefe: Leistungen einzeln erklaert, nicht als Aufzaehlung.

**Versteckter Kontakt.** Bei lokalem Handwerk wird angerufen, nicht
formularausgefuellt. Telefonnummer gehoert sichtbar in den Header, als
`tel:`-Link, und mobil zusaetzlich als fester Button am unteren Rand.

**Keine Vertrauenssignale.** Meisterbetrieb, Innung, Gruendungsjahr,
Mitarbeiterzahl, Garantien, Bewertungen. Ohne die ist es eine huebsche
Seite ohne Grund, dort anzurufen.

**Ungleiche Bilder.** Unterschiedliche Seitenverhaeltnisse, mal quer, mal
hoch, verschiedene Farbstimmungen nebeneinander. Einheitlich zuschneiden.
Notfalls ein leichter gemeinsamer Farbfilter.

## Handwerk

**Typografie.** Genau ein Schriftpaar. Fliesstext mindestens 17px, Zeilenhoehe
1.6, Zeilenlaenge 60-75 Zeichen. Ueberschriften deutlich groesser als der
Text - ein zu flacher Groessenunterschied wirkt kraftlos.

**Abstaende.** Aus einer festen Skala, nicht nach Gefuehl. Zwischen Sektionen
grosszuegig. Zusammengehoeriges enger als Getrenntes - Naehe zeigt
Zugehoerigkeit.

**Ausrichtung.** Alles auf einem gemeinsamen Raster. Ein um wenige Pixel
verschobener Block wirkt unsauber, auch wenn niemand sagen kann warum.

**Zustaende.** Jedes klickbare Element braucht Hover und sichtbaren Fokus.
Fehlende Hover-Zustaende sind ein klassisches Demo-Merkmal.

**Footer.** Vollstaendig, mit Adresse, Telefon, Oeffnungszeiten, Impressum,
Datenschutz. Ein duenner Footer entwertet die ganze Seite.

## Technik

- Mobil zuerst pruefen. Handwerkskunden kommen ueberwiegend vom Handy.
- Kein horizontales Scrollen bei 360px Breite.
- Bilder mit `width`/`height`, damit nichts springt.
- Alt-Texte, die den Inhalt beschreiben.
- Kontrast mindestens 4.5:1 fuer Fliesstext.
- Tastaturbedienung durchspielen: Tab durch die ganze Seite.
- `prefers-reduced-motion` respektieren.

## Letzte Pruefung

Zwei Fragen zum Schluss:

**Erkennt der Kunde seinen Betrieb wieder?** Wenn man Logo und Namen
austauschen koennte und es fiele niemandem auf, ist der Entwurf noch nicht
fertig.

**Sieht das aus wie das letzte Projekt?** Wenn ja: ueberarbeiten. Wiederholte
Handschrift ueber verschiedene Kunden hinweg faellt auf und wirkt wie
Fliessbandarbeit.
