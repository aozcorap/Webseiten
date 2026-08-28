# Sektionsmuster

Fertige Bausteine zu `basis.css`. Zusammensetzen statt neu erfinden - das
ist der Unterschied zwischen einem brauchbaren ersten Wurf und fuenf
Korrekturschleifen.

Reihenfolge fuer einen Handwerksbetrieb: Header, Hero, Vertrauensleiste,
Leistungen, Referenzen, Ablauf, Betrieb, Einzugsgebiet, Kontakt, Footer,
Anrufleiste. Das sind zehn Sektionen - unter sechs sieht es nach Demo aus.

## Kopf der Seite

Zuerst, sonst greift die Typografie nicht:

```html
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Maler Musterbetrieb - Maler und Lackierer in Friedberg</title>
  <meta name="description" content="Meisterbetrieb fuer Maler- und Lackierarbeiten in Friedberg und der Wetterau. Seit 1998.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&family=Source+Serif+4:opsz,wght@8..60,600;8..60,700&display=swap">
  <link rel="stylesheet" href="basis.css">
</head>
```

Ohne die Schrifteinbindung faellt die Seite auf Systemschriften zurueck -
ein haeufiger und sofort sichtbarer Fehler im ersten Wurf.

## Der wichtigste Fall: Leistungen

Hier entscheidet sich, ob die Seite nach KI aussieht.

**Nicht so** - drei gleich grosse Karten mit Piktogramm. Das deutlichste
Erkennungsmerkmal generierter Seiten:

```html
<!-- FALSCH -->
<div class="raster raster--3">
  <div class="karte"><svg>...</svg><h3>Malerarbeiten</h3><p>Professionell und zuverlaessig.</p></div>
  <div class="karte"><svg>...</svg><h3>Lackieren</h3><p>Hochwertige Ergebnisse.</p></div>
</div>
```

**Sondern so** - jede Leistung als eigener Block mit echtem Foto, Seite
wechselnd. Das erzeugt Rhythmus und zeigt die Arbeit statt sie zu behaupten:

```html
<section class="sektion" id="leistungen">
  <div class="wrap">
    <div class="kopf">
      <h2>Was wir machen</h2>
      <p>Vom einzelnen Raum bis zur kompletten Fassade.</p>
    </div>

    <div class="duo" style="margin-bottom: var(--a-8)">
      <img class="foto" src="./bilder/fassade-friedberg.jpg"
           width="800" height="600" loading="lazy"
           alt="Frisch gestrichene Hausfassade in hellem Beige, Friedberg">
      <div>
        <h3>Fassadenanstrich</h3>
        <p>Wortlaut vom Kunden: was genau gemacht wird, welche Materialien,
           wie lange es dauert. Zwei bis drei Saetze, keine Floskeln.</p>
        <a class="btn btn--leise" href="#kontakt">Fassade anfragen</a>
      </div>
    </div>

    <!-- naechster Block mit duo--gedreht, Bild wandert nach rechts -->
    <div class="duo duo--gedreht" style="margin-bottom: var(--a-8)">
      <div>
        <h3>Innenanstrich und Tapezieren</h3>
        <p>...</p>
      </div>
      <img class="foto" src="./bilder/wohnzimmer.jpg" width="800" height="600"
           loading="lazy" alt="Frisch tapeziertes Wohnzimmer mit heller Wand">
    </div>
  </div>
</section>
```

Erst wenn es mehr als sechs Leistungen gibt, wird eine kompakte Liste
sinnvoll - dann aber als Textliste, nicht als Icon-Kacheln.

## Hero

Foto-getragen, kein Farbverlauf. Was, wo, und die Telefonnummer.

```html
<section class="sektion" style="padding-top: var(--a-7)">
  <div class="wrap duo">
    <div>
      <h1>Maler- und Lackierarbeiten in Friedberg</h1>
      <p style="font-size: var(--t-l); color: var(--farbe-text-leise)">
        [Der eine Grund, diesen Betrieb zu nehmen - ein Satz aus der
        Bestandsaufnahme, nicht erfunden.]
      </p>
      <div style="display:flex; gap:var(--a-3); flex-wrap:wrap; margin-top:var(--a-6)">
        <a class="btn" href="tel:+4960311234567">06031 123456 anrufen</a>
        <a class="btn btn--leise" href="#referenzen">Arbeiten ansehen</a>
      </div>
    </div>
    <img class="foto foto--breit" src="./bilder/hero.jpg" width="1200" height="675"
         alt="Malermeister beim Streichen einer Altbaufassade">
  </div>
</section>
```

Keine Begruessungsfloskel. "Herzlich willkommen auf unserer Website" ist
verschenkter Platz an der wertvollsten Stelle der Seite.

## Vertrauensleiste

Direkt unter dem Hero, schmales Band. Nur echte Angaben.

```html
<div class="sektion--flaeche" style="padding-block: var(--a-5); border-block: 1px solid var(--farbe-linie)">
  <div class="wrap raster raster--3" style="gap: var(--a-4)">
    <p style="margin:0"><strong>Meisterbetrieb</strong><br>seit 1998</p>
    <p style="margin:0"><strong>Innung</strong><br>Maler und Lackierer Wetterau</p>
    <p style="margin:0"><strong>Umkreis 30 km</strong><br>rund um Friedberg</p>
  </div>
</div>
```

## Referenzen

Der wichtigste Abschnitt. Fotos mit Ort und Jahr - eine Galerie ohne
Beschriftung ueberzeugt niemanden.

```html
<section class="sektion sektion--flaeche" id="referenzen">
  <div class="wrap">
    <div class="kopf"><h2>Ausgefuehrte Arbeiten</h2></div>
    <div class="raster raster--3">
      <figure style="margin:0">
        <img class="foto" src="./bilder/ref-1.jpg" width="600" height="450"
             loading="lazy" alt="Sanierte Altbaufassade in Ockergelb">
        <figcaption style="margin-top:var(--a-3); font-size:var(--t-s); color:var(--farbe-text-leise)">
          <strong style="color:var(--farbe-text)">Fassadensanierung Altbau</strong><br>
          Friedberg, 2024
        </figcaption>
      </figure>
      <!-- weitere -->
    </div>
  </div>
</section>
```

Vorher-Nachher wirkt bei Maler, Garten und Sanierung besonders stark.
Zwei Bilder nebeneinander, gleich beschnitten, klar beschriftet.

## Ablauf

Nimmt die Unsicherheit, was nach dem Anruf passiert.

```html
<section class="sektion">
  <div class="wrap">
    <div class="kopf"><h2>So laeuft es ab</h2></div>
    <ol class="raster raster--3" style="list-style:none; padding:0; counter-reset:s">
      <li style="counter-increment:s">
        <div style="font-family:var(--schrift-titel); font-size:var(--t-2xl); color:var(--farbe-akzent); line-height:1">01</div>
        <h3>Anruf oder Nachricht</h3>
        <p>Sie schildern kurz, worum es geht.</p>
      </li>
      <!-- 02, 03, 04 -->
    </ol>
  </div>
</section>
```

## Kontakt

Kurz halten. Jedes Pflichtfeld mehr kostet Anfragen.

```html
<section class="sektion sektion--flaeche" id="kontakt">
  <div class="wrap duo">
    <div>
      <h2>Anfrage stellen</h2>
      <p>Am schnellsten geht es telefonisch.</p>
      <a class="tel btn" href="tel:+4960311234567">06031 123456</a>
      <p style="margin-top:var(--a-5); color:var(--farbe-text-leise)">
        Mo-Fr 7-17 Uhr<br>Musterstrasse 1, 61169 Friedberg
      </p>
    </div>
    <form>
      <div class="feld"><label for="n">Name</label><input id="n" name="name" required></div>
      <div class="feld"><label for="k">Telefon oder E-Mail</label><input id="k" name="kontakt" required></div>
      <div class="feld"><label for="a">Worum geht es?</label><textarea id="a" name="anliegen" rows="4"></textarea></div>
      <button class="btn" type="submit">Anfrage senden</button>
    </form>
  </div>
</section>
```

## Anrufleiste

Nur mobil sichtbar, immer erreichbar. `basis.css` bringt die Gestaltung mit.

```html
<div class="anrufleiste">
  <a class="btn" href="tel:+4960311234567">Anrufen</a>
  <a class="btn btn--leise" href="#kontakt">Nachricht</a>
</div>
```

## Footer

Vollstaendig. Ein duenner Footer entwertet die ganze Seite.

Adresse, Telefon als `tel:`-Link, E-Mail, Oeffnungszeiten, Einzugsgebiet als
Ortsliste, Links zu Impressum und Datenschutz.
