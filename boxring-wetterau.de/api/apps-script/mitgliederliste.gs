/**
 * Google Apps Script fuer das Sheet "Boxring Wetterau – Mitgliederliste".
 *
 * Zweck: PHP (api/anmeldung.php) schickt bei jeder Online-Anmeldung einen
 * simplen HTTP-POST hierher, dieses Skript haengt die Zeile an und vergibt
 * dabei die naechste freie Mitgliedsnummer. Kein Google-Cloud-Projekt, keine
 * Service-Account-Datei noetig - das Skript hat als Teil des Sheets automatisch
 * Schreibrechte auf genau dieses Sheet.
 *
 * EINRICHTUNG (einmalig, ca. 3 Minuten):
 * 1. Das Sheet "Boxring Wetterau – Mitgliederliste" in Google Sheets oeffnen.
 * 2. Menü: Erweiterungen -> Apps Script.
 * 3. Den kompletten Inhalt dieser Datei in den Editor einfuegen (vorhandenen
 *    Beispielcode ersetzen).
 * 4. In Zeile ~20 SHARED_SECRET durch ein eigenes, langes Zufallspasswort
 *    ersetzen (z.B. mit einem Passwort-Generator erzeugen). Dasselbe Secret
 *    muss anschliessend in api/config.php unter GOOGLE_SHEETS_WEBAPP_SECRET
 *    eingetragen werden.
 * 5. Oben rechts "Bereitstellen" -> "Neue Bereitstellung".
 *    - Typ: "Web-App"
 *    - Ausfuehren als: "Ich" (dein Google-Konto)
 *    - Zugriff: "Jeder" (das Secret in Schritt 4 schuetzt den Endpunkt)
 * 6. Nach dem Bestaetigen der Berechtigungen (Google fragt, ob das Skript auf
 *    das Sheet zugreifen darf - das ist noetig und erwartet) die angezeigte
 *    Web-App-URL kopieren (endet auf ".../exec").
 * 7. Diese URL in api/config.php unter GOOGLE_SHEETS_WEBAPP_URL eintragen.
 *
 * Bei einer erneuten Aenderung dieses Skripts: erneut "Bereitstellen" ->
 * "Bereitstellungen verwalten" -> Stift-Symbol -> neue Version waehlen ->
 * "Bereitstellen" (die URL bleibt dabei gleich).
 */

var SHARED_SECRET = 'RcY_ogqOfuZqECmOGqcDA-FC16eQnqkyGJ2AcU0rXHQ';
var MITGLIEDSNR_SPALTE = 7; // Spalte G

// Spalten, die trotz rein numerischem Inhalt IMMER als Text gespeichert
// werden muessen, weil Google Sheets sonst fuehrende Nullen verschluckt
// (z.B. Telefonnummer 0173... -> 173..., PLZ 01067 Dresden -> 1067). Ein
// fuehrendes Apostroph zwingt Sheets zur Text-Interpretation - unabhaengig
// davon, wie die Spalte gerade formatiert ist (robuster als sich auf die
// manuelle "Nur Text"-Formatierung der Spalte zu verlassen).
var TELEFON_SPALTE = 14; // Spalte N
var PLZ_SPALTE = 11; // Spalte K

function doPost(e) {
  try {
    var payload = JSON.parse(e.postData.contents);

    if (!payload || payload.secret !== SHARED_SECRET) {
      return jsonResponse({ success: false, message: 'unauthorized' });
    }

    // Nimmt einfach das erste (aktuelle) Tabellenblatt, unabhaengig vom Namen.
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheets()[0];
    if (!sheet) {
      return jsonResponse({ success: false, message: 'Kein Tabellenblatt gefunden' });
    }

    // Mitglied-Check (Trainer-Adminbereich, api/mitglied-suche.php): sucht
    // nur Vor-/Nachname ab und gibt bewusst NUR unkritische Eckdaten zurueck
    // (keine IBAN/Adresse/Telefon/Mail/Geburtstag) - Trainer sollen lediglich
    // pruefen koennen, ob jemand Mitglied ist, nicht die volle Kartei sehen.
    if (payload.action === 'search') {
      return jsonResponse(sucheMitglied(sheet, payload.vorname, payload.nachname));
    }

    var row = payload.row;
    if (!Array.isArray(row)) {
      return jsonResponse({ success: false, message: 'row fehlt oder ist kein Array' });
    }

    var neueMitgliedsnr = naechsteMitgliedsnr(sheet);
    row[MITGLIEDSNR_SPALTE - 1] = neueMitgliedsnr;

    row[TELEFON_SPALTE - 1] = alsText(row[TELEFON_SPALTE - 1]);
    row[PLZ_SPALTE - 1] = alsText(row[PLZ_SPALTE - 1]);

    sheet.appendRow(row);

    return jsonResponse({ success: true, mitgliedsnr: neueMitgliedsnr });
  } catch (err) {
    return jsonResponse({ success: false, message: String(err) });
  }
}

function sucheMitglied(sheet, vorname, nachname) {
  var lastRow = sheet.getLastRow();
  if (lastRow < 2) {
    return { success: true, gefunden: false, treffer: [] };
  }
  // Spalten A:R = 18 Spalten (siehe SETUP.md fuer die vollstaendige Reihenfolge).
  var values = sheet.getRange(2, 1, lastRow - 1, 18).getValues();
  var qVorname = normalisiere(vorname);
  var qNachname = normalisiere(nachname);

  var treffer = [];
  values.forEach(function (r) {
    if (normalisiere(r[2]) === qVorname && normalisiere(r[3]) === qNachname) {
      treffer.push(zeileZuTreffer(r));
    }
  });

  // Fallback bei keinem exakten Treffer: tolerantere Teilstring-Suche, falls
  // sich z.B. jemand beim Tippen waehrend des Trainings vertippt hat.
  if (treffer.length === 0 && qVorname !== '' && qNachname !== '') {
    values.forEach(function (r) {
      if (normalisiere(r[2]).indexOf(qVorname) !== -1 && normalisiere(r[3]).indexOf(qNachname) !== -1) {
        treffer.push(zeileZuTreffer(r));
      }
    });
  }

  return { success: true, gefunden: treffer.length > 0, treffer: treffer };
}

function normalisiere(wert) {
  return String(wert || '').trim().toLowerCase();
}

function zeileZuTreffer(r) {
  return {
    vorname: r[2],
    nachname: r[3],
    mitgliedsnr: r[6],
    eintritt: r[16],
    gekuendigtJahresende: !!r[0],
  };
}

/** Erzwingt Text-Speicherung per fuehrendem Apostroph, damit fuehrende Nullen erhalten bleiben. */
function alsText(value) {
  if (value === null || value === undefined || value === '') {
    return value;
  }
  var text = String(value);
  return text.charAt(0) === "'" ? text : "'" + text;
}

function naechsteMitgliedsnr(sheet) {
  var lastRow = sheet.getLastRow();
  if (lastRow < 2) {
    return 1;
  }
  var values = sheet.getRange(2, MITGLIEDSNR_SPALTE, lastRow - 1, 1).getValues();
  var max = 0;
  values.forEach(function (r) {
    var v = parseInt(r[0], 10);
    if (!isNaN(v) && v > max) {
      max = v;
    }
  });
  return max + 1;
}

function jsonResponse(obj) {
  return ContentService.createTextOutput(JSON.stringify(obj)).setMimeType(ContentService.MimeType.JSON);
}
