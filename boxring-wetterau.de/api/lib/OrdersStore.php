<?php
declare(strict_types=1);

require_once __DIR__ . '/JsonFileStore.php';

/**
 * Persistente Speicherung der Shop-Bestellungen in api/data/orders.json
 * (per .htaccess von aussen gesperrt, siehe api/data/.htaccess). Die
 * Bestellnummer wird hier serverseitig vergeben (fortlaufender Zaehler in
 * derselben Datei) - eine clientseitig hochgezaehlte Nummer waere pro
 * Browser-Tab bei 1000 gestartet und haette sich zwischen verschiedenen
 * Kunden garantiert wiederholt.
 */
final class OrdersStore
{
    private const PATH = __DIR__ . '/../data/orders.json';
    private const DEFAULT = ['nextSeq' => 1000, 'orders' => []];

    /** @param array{member:string,email:string,items:array,total:float,paymentMethod:string,createdAt:string} $orderData */
    public static function create(array $orderData): array
    {
        return JsonFileStore::withLock(self::PATH, self::DEFAULT, function (array $data) use ($orderData) {
            $seq = ((int) ($data['nextSeq'] ?? 1000)) + 1;
            $order = array_merge(['id' => 'BRW-' . $seq, 'status' => 'offen', 'paidVia' => null, 'paidAt' => null], $orderData);
            $data['orders'][] = $order;
            $data['nextSeq'] = $seq;
            return [$data, $order];
        });
    }

    public static function all(): array
    {
        return JsonFileStore::withLock(self::PATH, self::DEFAULT, function (array $data) {
            $orders = $data['orders'] ?? [];
            usort($orders, fn (array $a, array $b) => strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? ''));
            return [null, $orders];
        });
    }

    public static function markPaid(string $id, string $paidVia): bool
    {
        return JsonFileStore::withLock(self::PATH, self::DEFAULT, function (array $data) use ($id, $paidVia) {
            $found = false;
            foreach ($data['orders'] as &$order) {
                if ($order['id'] === $id) {
                    $order['status'] = 'bezahlt';
                    $order['paidVia'] = $paidVia;
                    $order['paidAt'] = (new DateTimeImmutable('now', new DateTimeZone('Europe/Berlin')))->format('d.m.Y H:i');
                    $found = true;
                }
            }
            unset($order);
            return [$found ? $data : null, $found];
        });
    }

    public static function markOpen(string $id): bool
    {
        return JsonFileStore::withLock(self::PATH, self::DEFAULT, function (array $data) use ($id) {
            $found = false;
            foreach ($data['orders'] as &$order) {
                if ($order['id'] === $id) {
                    $order['status'] = 'offen';
                    $order['paidVia'] = null;
                    $order['paidAt'] = null;
                    $found = true;
                }
            }
            unset($order);
            return [$found ? $data : null, $found];
        });
    }

    public static function delete(string $id): bool
    {
        return JsonFileStore::withLock(self::PATH, self::DEFAULT, function (array $data) use ($id) {
            $before = count($data['orders']);
            $data['orders'] = array_values(array_filter($data['orders'], fn (array $o) => $o['id'] !== $id));
            $found = count($data['orders']) < $before;
            return [$found ? $data : null, $found];
        });
    }
}
