<?php
// pemeriksa/cek_cepat.php
// Pemeriksa cepat 25 tiket utama + 5 tiket bonus di laptop peserta (< 5 detik)
// Mendukung mode 100% OFFLINE (tanpa Wi-Fi, tanpa server proyektor)

require_once __DIR__ . '/../app/Support/Database.php';

use App\Support\Database;
use App\Controllers\OrderController;
use App\Controllers\SearchController;
use App\Controllers\InvoiceController;
use App\Controllers\AuthController;
use App\Services\CartService;

echo "======================================================================\n";
echo "           PAPAN KONTROL PROGRES TIKET LOKAL (OFFLINE MODE)           \n";
echo "======================================================================\n";

$tickets = [
    // [Kode, Judul, Kategori, Poin, Callback Verifikasi]
    'T01' => ['Null Address Guard', 'Bug Fix', 10, function() {
        $c = new OrderController();
        $r = $c->checkout(['user_id' => 3, 'product_id' => 102, 'quantity' => 1]);
        return ($r['code'] === 400 && str_contains($r['message'], 'Alamat'));
    }],
    'T02' => ['Division By Zero', 'Bug Fix', 10, function() {
        $cart = new CartService();
        $r = $cart->calculateTotal([['name' => 'Item', 'price' => 50000, 'quantity' => 1]], 'GRATIS100');
        return ($r['final_total'] === 0 && isset($r['savings_ratio']));
    }],
    'T03' => ['State Machine Bypass', 'Bug Fix', 10, function() {
        $c = new OrderController();
        Database::execute("UPDATE orders SET status = 'DELIVERED' WHERE id = 1");
        $r = $c->cancelOrder(1);
        return ($r['code'] === 422);
    }],
    'T04' => ['Race Condition Stok', 'Concurrency', 10, function() {
        $code = file_get_contents(__DIR__ . '/../app/Controllers/OrderController.php');
        return (str_contains($code, 'stock - ?') && str_contains($code, 'stock >='));
    }],
    'T05' => ['Missing Foreign Key Index', 'Indexing', 10, function() {
        $idx1 = Database::query("SHOW INDEX FROM orders WHERE Column_name = 'user_id'");
        $idx2 = Database::query("SHOW INDEX FROM order_items WHERE Column_name = 'order_id'");
        return (!empty($idx1) && !empty($idx2));
    }],
    'T06' => ['N+1 Query Eliminasi', 'Performance', 10, function() {
        Database::resetQueryCount();
        $c = new OrderController();
        $r = $c->listOrders();
        return ($r['total_queries'] <= 3);
    }],
    'T07' => ['Sargable Date Range Query', 'Indexing', 10, function() {
        $c = new OrderController();
        if (method_exists($c, 'getSalesRekapByYear')) {
            $r = $c->getSalesRekapByYear(2024);
            return isset($r['total_orders']);
        }
        return class_exists('Kunci\KunciT07');
    }],
    'T08' => ['Expand-Contract Schema', 'Zero Downtime', 10, function() {
        $cols = Database::query("SHOW COLUMNS FROM orders LIKE 'tracking_code'");
        return !empty($cols);
    }],
    'T09' => ['Backward Compatible API', 'API Contract', 10, function() {
        if (method_exists(OrderController::class, 'formatOrderResponse')) {
            $r = OrderController::formatOrderResponse(['id' => 1, 'address' => 'Jakarta']);
            return isset($r['address']) && isset($r['address_detail']);
        }
        return class_exists('Kunci\KunciT09');
    }],
    'T10' => ['Sprout Class Pajak PPN 11%', 'Seams/Sprout', 10, function() {
        $cls = class_exists('App\Services\TaxCalculatorSprout') ? 'App\Services\TaxCalculatorSprout' : (class_exists('Kunci\TaxCalculatorSprout') ? 'Kunci\TaxCalculatorSprout' : null);
        if (!$cls) return false;
        $inst = new $cls();
        return ($inst->calculateTax(100000) == 11000.0);
    }],
    'T11' => ['Payment Adapter Pattern', 'Architecture', 10, function() {
        $clsM = class_exists('App\Services\Payment\MidtransAdapter') ? 'App\Services\Payment\MidtransAdapter' : (class_exists('Kunci\MidtransAdapter') ? 'Kunci\MidtransAdapter' : null);
        $clsX = class_exists('App\Services\Payment\XenditAdapter') ? 'App\Services\Payment\XenditAdapter' : (class_exists('Kunci\XenditAdapter') ? 'Kunci\XenditAdapter' : null);
        return ($clsM !== null && $clsX !== null);
    }],
    'T12' => ['Idempotency Guard Redis', 'Idempotency', 10, function() {
        return class_exists('App\Support\IdempotencyGuard') || class_exists('Kunci\IdempotencyGuard');
    }],
    'T13' => ['Order Notification DTO', 'Type Safety', 10, function() {
        return class_exists('App\DTO\OrderNotificationDTO') || class_exists('Kunci\OrderNotificationDTO');
    }],
    'T14' => ['Email Queue Producer', 'Async Queue', 10, function() {
        return class_exists('App\Services\EmailQueueProducer') || class_exists('Kunci\EmailQueueProducer');
    }],
    'T15' => ['Async Invoice Handler', 'Async Queue', 10, function() {
        return class_exists('App\Services\AsyncInvoiceHandler') || class_exists('Kunci\AsyncInvoiceHandler');
    }],
    'T16' => ['Stored XSS Sanitization', 'Security', 10, function() {
        $cls = class_exists('App\Support\HtmlSanitizer') ? 'App\Support\HtmlSanitizer' : (class_exists('Kunci\HtmlSanitizer') ? 'Kunci\HtmlSanitizer' : null);
        if (!$cls) return false;
        return !str_contains($cls::escape("<script>"), '<script>');
    }],
    'T17' => ['SQL Injection Parameterized', 'Security', 10, function() {
        $s = new SearchController();
        $r = $s->search("' OR '1'='1");
        return (count($r) === 0);
    }],
    'T18' => ['IDOR Prevention Invoice', 'Security', 10, function() {
        $inv = new InvoiceController();
        $r = $inv->download(1002, 1);
        return in_array($r['code'], [403, 404]);
    }],
    'T19' => ['Bcrypt Password Upgrade', 'Security', 10, function() {
        $u = Database::query("SELECT password FROM users WHERE id = 1")[0] ?? [];
        return str_starts_with($u['password'] ?? '', '$2y$');
    }],
    'T20' => ['Characterization Snapshot', 'Testing', 10, function() {
        return file_exists(__DIR__ . '/../uji/tests/Tickets/Tkt20Test.php');
    }],
    'T21' => ['Mock Shipping API Client', 'Testing', 10, function() {
        return class_exists('App\Services\MockShippingApiClient') || class_exists('Kunci\MockShippingApiClient');
    }],
    'T22' => ['Architecture Rules (Pest)', 'Architecture', 10, function() {
        $code = file_get_contents(__DIR__ . '/../app/Controllers/OrderController.php');
        return !str_contains($code, 'new PDO(');
    }],
    'T23' => ['Eliminasi Dead Routes', 'Clean Code', 10, function() {
        $code = file_get_contents(__DIR__ . '/../app/public/index.php');
        return !str_contains($code, '/promo/harbolnas-2021');
    }],
    'T24' => ['Phone Cleansing E.164', 'Data Quality', 10, function() {
        $users = Database::query("SELECT phone FROM users WHERE phone LIKE '08%'");
        return empty($users);
    }],
    'T25' => ['PHPStan Type Safety Helper', 'Type Safety', 10, function() {
        return class_exists('App\Support\Helpers') || class_exists('Kunci\Helpers');
    }],
];

$bonusTickets = [
    'B01' => ['Circuit Breaker Pattern', 'Resilience', 30, function() {
        return class_exists('App\Services\Payment\CircuitBreaker') || class_exists('Kunci\CircuitBreaker');
    }],
    'B02' => ['Transactional Outbox Pattern', 'Distributed', 35, function() {
        $t = Database::query("SHOW TABLES LIKE 'outbox_events'");
        return !empty($t);
    }],
    'B03' => ['Automated Rector AST Rule', 'DevEx', 25, function() {
        return file_exists(__DIR__ . '/../rector.php');
    }],
    'B04' => ['Mutation Testing Infection', 'Testing Rigor', 25, function() {
        return file_exists(__DIR__ . '/../infection.json');
    }],
    'B05' => ['Read-Write Splitting DB', 'Scaling', 30, function() {
        return method_exists(Database::class, 'getReadConnection');
    }],
];

$totalPoin = 0;
$maxPoin = 250;
$lulusCount = 0;

echo sprintf("%-6s | %-30s | %-14s | %-5s | %-8s\n", "KODE", "JUDUL TIKET", "KATEGORI", "POIN", "STATUS");
echo str_repeat("-", 70) . "\n";

foreach ($tickets as $kode => [$judul, $kat, $poin, $checker]) {
    $lulus = false;
    try {
        $lulus = (bool)$checker();
    } catch (\Throwable $e) {
        $lulus = false;
    }

    if ($lulus) {
        $statusStr = "\033[32m🟢 LULUS\033[0m";
        $totalPoin += $poin;
        $lulusCount++;
    } else {
        $statusStr = "\033[31m🔴 BELUM\033[0m";
    }

    echo sprintf("%-6s | %-30s | %-14s | %-5s | %s\n", $kode, substr($judul, 0, 30), $kat, $poin, $statusStr);
}

// Cek Tiket Bonus
$bonusPoin = 0;
$bonusLulus = 0;
$totalBonusPoin = 145;

echo "\n" . str_repeat("=", 70) . "\n";
echo " 💀 TIKET BONUS (BOSS LEVEL - OPSIONAL)\n";
echo str_repeat("-", 70) . "\n";

foreach ($bonusTickets as $kode => [$judul, $kat, $poin, $checker]) {
    $lulus = false;
    try {
        $lulus = (bool)$checker();
    } catch (\Throwable $e) {
        $lulus = false;
    }

    if ($lulus) {
        $statusStr = "\033[32m⭐ SELESAI\033[0m";
        $bonusPoin += $poin;
        $bonusLulus++;
    } else {
        $statusStr = "\033[33m⏳ TERKUNCI\033[0m";
    }

    echo sprintf("%-6s | %-30s | %-14s | %-5s | %s\n", $kode, substr($judul, 0, 30), $kat, "+" . $poin, $statusStr);
}

echo str_repeat("=", 70) . "\n";
$persen = round(($totalPoin / $maxPoin) * 100, 1);
$totalSemuaPoin = $totalPoin + $bonusPoin;

echo sprintf("TOTAL SKOR TIKET WAJIB : %d / %d Pts (%s%%) [%d/25 Tiket Selesai]\n", $totalPoin, $maxPoin, $persen, $lulusCount);
if ($bonusPoin > 0) {
    echo sprintf("TOTAL SKOR TIKET BONUS : +%d Pts [%d/5 Bonus Selesai]\n", $bonusPoin, $bonusLulus);
}
echo sprintf("TOTAL AKUMULASI POIN   : %d Pts\n", $totalSemuaPoin);

if ($totalPoin >= 200) {
    echo "\n🏆 STATUS AKREDITASI: SENIOR / LEAD ENGINEER TIER\n";
} elseif ($totalPoin >= 150) {
    echo "\n🎯 STATUS AKREDITASI: MID-LEVEL BACKEND ENGINEER TIER\n";
} elseif ($totalPoin >= 100) {
    echo "\n🚀 STATUS AKREDITASI: JUNIOR BACKEND READY (PASS GATE K5)\n";
} else {
    echo "\n🛠️ STATUS AKREDITASI: ON-PROGRESS (Lanjutkan penyelesaian tiket)\n";
}
echo "======================================================================\n";
