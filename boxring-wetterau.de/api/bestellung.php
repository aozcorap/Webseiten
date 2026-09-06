<?php
declare(strict_types=1);

/**
 * Verarbeitet eine Shop-Bestellung aus shop/index.html: validiert, vergibt
 * serverseitig eine Bestellnummer, speichert die Bestellung dauerhaft (siehe
 * OrdersStore) und verschickt die Zahlungsdetails (Überweisung oder Bar im
 * Training) per E-Mail an das bestellende Mitglied, mit CC an die
 * Zeugwart:in, damit sie ueber jede Bestellung informiert ist.
 *
 * Die Bestellung wird VOR dem Mailversand gespeichert: schlaegt die Mail
 * fehl, ist die Bestellung trotzdem im Adminbereich sichtbar und nicht
 * verloren - nur die Benachrichtigung ist dann nachzuholen.
 *
 * Der Preis kommt unveraendert vom Client (kein Produktkatalog auf dem
 * Server) - fuer diesen kurzlebigen, vereinsinternen Shop bewusst in Kauf
 * genommen, siehe shop/README.md.
 */

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/lib/Validation.php';
require_once __DIR__ . '/lib/Mailer.php';
require_once __DIR__ . '/lib/OrdersStore.php';

$configPath = __DIR__ . '/config.php';
if (!is_file($configPath)) {
    http_response_code(500);
    error_log('bestellung.php: config.php fehlt - siehe config.sample.php');
    echo json_encode(['success' => false, 'message' => 'Der Server ist noch nicht vollstaendig eingerichtet. Bitte kontaktiere uns direkt.']);
    exit;
}
require_once $configPath;

function respond(int $httpCode, array $payload): void
{
    http_response_code($httpCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(405, ['success' => false, 'message' => 'Methode nicht erlaubt.']);
}

$raw = file_get_contents('php://input');
$input = json_decode((string) $raw, true);
if (!is_array($input)) {
    respond(400, ['success' => false, 'message' => 'Ungueltige Anfrage.']);
}

// Honeypot: Bots fuellen versteckte Felder aus, echte Nutzer nie.
if (!empty($input['website'])) {
    respond(200, ['success' => true]);
}

$name = Validation::clean(isset($input['name']) && is_string($input['name']) ? $input['name'] : null);
$email = Validation::clean(isset($input['email']) && is_string($input['email']) ? $input['email'] : null);
$paymentMethod = Validation::clean(isset($input['paymentMethod']) && is_string($input['paymentMethod']) ? $input['paymentMethod'] : null);
$items = isset($input['items']) && is_array($input['items']) ? $input['items'] : [];

$errors = [];
if ($name === null) {
    $errors[] = 'Name fehlt.';
}
if ($email === null || !Validation::emailValid($email)) {
    $errors[] = 'E-Mail-Adresse ist ungueltig.';
}
if (!in_array($paymentMethod, ['ueberweisung', 'bar'], true)) {
    $errors[] = 'Zahlungsart ist ungueltig.';
}
if (empty($items)) {
    $errors[] = 'Bestellung enthaelt keine Artikel.';
}

$cleanItems = [];
$total = 0.0;
foreach ($items as $item) {
    if (!is_array($item)) {
        continue;
    }
    $itemId = Validation::clean(isset($item['id']) && is_string($item['id']) ? $item['id'] : null);
    $itemName = Validation::clean(isset($item['name']) && is_string($item['name']) ? $item['name'] : null);
    $itemSize = Validation::clean(isset($item['sizeValue']) && is_string($item['sizeValue']) ? $item['sizeValue'] : null);
    $itemLabel = Validation::clean(isset($item['size']) && is_string($item['size']) ? $item['size'] : null);
    $itemColor = Validation::clean(isset($item['color']) && is_string($item['color']) ? $item['color'] : null);
    $itemHasLogo = !empty($item['hasLogo']);
    $itemPrice = isset($item['price']) && is_numeric($item['price']) ? (float) $item['price'] : null;
    if ($itemName === null || $itemPrice === null || $itemPrice < 0) {
        continue;
    }
    $cleanItems[] = [
        'id' => $itemId ?? '-',
        'name' => $itemName,
        'size' => $itemSize ?? '-',
        'label' => $itemLabel ?? ($itemSize ?? '-'),
        'color' => $itemColor ?? '-',
        'hasLogo' => $itemHasLogo,
        'price' => $itemPrice,
    ];
    $total += $itemPrice;
}
if (empty($cleanItems)) {
    $errors[] = 'Bestellung enthaelt keine gueltigen Artikel.';
}

if (!empty($errors)) {
    respond(422, ['success' => false, 'message' => 'Bitte pruefe deine Angaben: ' . implode(' ', $errors)]);
}

$order = OrdersStore::create([
    'member' => $name,
    'email' => $email,
    'items' => $cleanItems,
    'total' => $total,
    'paymentMethod' => $paymentMethod === 'ueberweisung' ? 'Überweisung' : 'Bar im Training',
    'createdAt' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('Y-m-d H:i:s'),
]);
$orderId = $order['id'];

$fmt = static fn (float $n): string => number_format($n, 2, ',', '.') . ' €';

$itemsHtml = '';
foreach ($cleanItems as $item) {
    $itemsHtml .= sprintf(
        '<tr><td>%s (Größe %s)</td><td style="text-align:right;">%s</td></tr>',
        htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($fmt($item['price']), ENT_QUOTES, 'UTF-8')
    );
}

if ($paymentMethod === 'ueberweisung') {
    $paymentHtml = sprintf(
        '<p>Bitte überweise den Betrag zeitnah auf folgendes Konto:</p>' .
        '<table cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;">' .
        '<tr><td>Empfänger</td><td>%s</td></tr>' .
        '<tr><td>IBAN</td><td>%s</td></tr>' .
        '<tr><td>Betrag</td><td>%s</td></tr>' .
        '<tr><td>Verwendungszweck</td><td>%s %s</td></tr>' .
        '</table>',
        htmlspecialchars(SHOP_KONTOINHABER, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars(SHOP_IBAN, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($fmt($total), ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($orderId, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($name, ENT_QUOTES, 'UTF-8')
    );
} else {
    $paymentHtml = sprintf(
        '<p>Bitte bezahle den Betrag von <strong>%s</strong> in bar beim nächsten Training bei %s. ' .
        'Bitte dabei die Bestellnummer <strong>%s</strong> nennen.</p>',
        htmlspecialchars($fmt($total), ENT_QUOTES, 'UTF-8'),
        htmlspecialchars(SHOP_KONTOINHABER, ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($orderId, ENT_QUOTES, 'UTF-8')
    );
}

$bodyHtml = sprintf(
    '<p>Hallo %s,</p>' .
    '<p>danke für deine Bestellung im Vereinsshop. Bestellnummer: <strong>%s</strong></p>' .
    '<table cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;width:100%%;">%s' .
    '<tr><td><strong>Gesamt</strong></td><td style="text-align:right;"><strong>%s</strong></td></tr>' .
    '</table>' .
    '%s' .
    '<p>Die Bestellung ist erst verbindlich, sobald die Zahlung eingegangen ist.</p>' .
    '<p>Bei Fragen melde dich jederzeit gerne unter <a href="mailto:%s">%s</a>.</p>' .
    '<p>Sportliche Grüße,<br>Boxring Wetterau 1983 e.V.</p>',
    htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars($orderId, ENT_QUOTES, 'UTF-8'),
    $itemsHtml,
    htmlspecialchars($fmt($total), ENT_QUOTES, 'UTF-8'),
    $paymentHtml,
    htmlspecialchars(SHOP_NOTIFY_EMAIL, ENT_QUOTES, 'UTF-8'),
    htmlspecialchars(SHOP_NOTIFY_EMAIL, ENT_QUOTES, 'UTF-8')
);

try {
    Mailer::send(
        $email,
        $name,
        'Deine Bestellung im Vereinsshop – ' . $orderId,
        $bodyHtml,
        null,
        [SHOP_NOTIFY_EMAIL],
        [],
        SHOP_NOTIFY_EMAIL,
        SHOP_KONTOINHABER,
        SHOP_NOTIFY_EMAIL,
        SHOP_KONTOINHABER
    );
} catch (Throwable $e) {
    error_log('bestellung.php: Mailversand fehlgeschlagen: ' . $e->getMessage());
    // Bestellung ist bereits gespeichert (siehe oben) - nur die Mail fehlt.
    respond(502, [
        'success' => false,
        'orderId' => $orderId,
        'message' => 'Deine Bestellung ' . $orderId . ' ist eingegangen, aber die Bestätigungsmail konnte nicht verschickt werden. Bitte notiere dir die Bestellnummer und melde dich bei uns.',
    ]);
}

respond(200, ['success' => true, 'orderId' => $orderId]);
