<?php
// pemeriksa/cek_cepat.php
// Pemeriksa tiket mandiri dengan mode Blind Testing & Telemetri Otomatis ke Mentor.
// Menyembunyikan total tiket dan poin dari peserta agar fokus pada investigasi kode.

require_once __DIR__ . '/../app/Support/Database.php';
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use App\Support\Database;
use App\Controllers\OrderController;
use App\Controllers\SearchController;
use App\Controllers\InvoiceController;
use App\Controllers\AuthController;
use App\Services\CartService;

// 1. Muat Konfigurasi Peserta (peserta_config.json diprioritaskan)
$configFile = __DIR__ . '/../peserta_config.json';
$config = [];
if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true) ?: [];
}

$namaPeserta = !empty($config['nama_peserta']) ? $config['nama_peserta'] : (getenv('NAMA_PESERTA') ?: 'Peserta_Meja01');
$nomorMeja   = !empty($config['meja']) ? $config['meja'] : (getenv('NOMOR_MEJA') ?: 'Meja 01');
$mentorUrl   = !empty($config['mentor_server_url']) ? $config['mentor_server_url'] : (getenv('MENTOR_URL') ?: 'http://web/papan');

// 2. Definisi Seluruh Tiket dan Logika Pengujian
$allCheckers = [
    'T01' => ['Null Address Guard', function() {
        $c = new OrderController();
        $r = $c->checkout(['user_id' => 3, 'product_id' => 102, 'quantity' => 1]);
        return ($r['code'] === 400 && str_contains($r['message'], 'Alamat'));
    }],
    'T02' => ['Division By Zero', function() {
        $cart = new CartService();
        $r = $cart->calculateTotal([['name' => 'Item', 'price' => 50000, 'quantity' => 1]], 'GRATIS100');
        return ($r['final_total'] === 0 && isset($r['savings_ratio']));
    }],
    'T03' => ['State Machine Bypass', function() {
        $c = new OrderController();
        Database::execute("UPDATE orders SET status = 'DELIVERED' WHERE id = 1");
        $r = $c->cancelOrder(1);
        return ($r['code'] === 422);
    }],
    'T04' => ['Race Condition Stok', function() {
        $code = file_get_contents(__DIR__ . '/../app/Controllers/OrderController.php');
        return (str_contains($code, 'stock - ?') && str_contains($code, 'stock >='));
    }],
    'T05' => ['Missing Foreign Key Index', function() {
        $idx1 = Database::query("SHOW INDEX FROM orders WHERE Column_name = 'user_id'");
        $idx2 = Database::query("SHOW INDEX FROM order_items WHERE Column_name = 'order_id'");
        return (!empty($idx1) && !empty($idx2));
    }],
    'T06' => ['N+1 Query Eliminasi', function() {
        Database::resetQueryCount();
        $c = new OrderController();
        $r = $c->listOrders();
        return ($r['total_queries'] <= 3);
    }],
    'T07' => ['Sargable Date Range Query', function() {
        $c = new OrderController();
        if (method_exists($c, 'getSalesRekapByYear')) {
            $r = $c->getSalesRekapByYear(2024);
            return isset($r['total_orders']);
        }
        return class_exists('Kunci\KunciT07');
    }],
    'T08' => ['Expand-Contract Schema', function() {
        $cols = Database::query("SHOW COLUMNS FROM orders LIKE 'tracking_code'");
        return !empty($cols);
    }],
    'T09' => ['Backward Compatible API', function() {
        if (method_exists(OrderController::class, 'formatOrderResponse')) {
            $r = OrderController::formatOrderResponse(['id' => 1, 'address' => 'Jakarta']);
            return isset($r['address']) && isset($r['address_detail']);
        }
        return class_exists('Kunci\KunciT09');
    }],
    'T10' => ['Sprout Class Pajak PPN 11%', function() {
        $cls = class_exists('App\Services\TaxCalculatorSprout') ? 'App\Services\TaxCalculatorSprout' : (class_exists('Kunci\TaxCalculatorSprout') ? 'Kunci\TaxCalculatorSprout' : null);
        if (!$cls) return false;
        $inst = new $cls();
        return ($inst->calculateTax(100000) == 11000.0);
    }],
    'T11' => ['Payment Adapter Pattern', function() {
        $clsM = class_exists('App\Services\Payment\MidtransAdapter') ? 'App\Services\Payment\MidtransAdapter' : (class_exists('Kunci\MidtransAdapter') ? 'Kunci\MidtransAdapter' : null);
        $clsX = class_exists('App\Services\Payment\XenditAdapter') ? 'App\Services\Payment\XenditAdapter' : (class_exists('Kunci\XenditAdapter') ? 'Kunci\XenditAdapter' : null);
        return ($clsM !== null && $clsX !== null);
    }],
    'T12' => ['Idempotency Guard Redis', function() {
        return class_exists('App\Support\IdempotencyGuard') || class_exists('Kunci\IdempotencyGuard');
    }],
    'T13' => ['Order Notification DTO', function() {
        return class_exists('App\DTO\OrderNotificationDTO') || class_exists('Kunci\OrderNotificationDTO');
    }],
    'T14' => ['Email Queue Producer', function() {
        return class_exists('App\Services\EmailQueueProducer') || class_exists('Kunci\EmailQueueProducer');
    }],
    'T15' => ['Async Invoice Handler', function() {
        return class_exists('App\Services\AsyncInvoiceHandler') || class_exists('Kunci\AsyncInvoiceHandler');
    }],
    'T16' => ['Stored XSS Sanitization', function() {
        $cls = class_exists('App\Support\HtmlSanitizer') ? 'App\Support\HtmlSanitizer' : (class_exists('Kunci\HtmlSanitizer') ? 'Kunci\HtmlSanitizer' : null);
        if (!$cls) return false;
        return !str_contains($cls::escape("<script>"), '<script>');
    }],
    'T17' => ['SQL Injection Parameterized', function() {
        $s = new SearchController();
        $r = $s->search("' OR '1'='1");
        return (count($r) === 0);
    }],
    'T18' => ['IDOR Prevention Invoice', function() {
        $inv = new InvoiceController();
        $r = $inv->download(1002, 1);
        return in_array($r['code'], [403, 404]);
    }],
    'T19' => ['Bcrypt Password Upgrade', function() {
        $u = Database::query("SELECT password FROM users WHERE id = 1")[0] ?? [];
        return str_starts_with($u['password'] ?? '', '$2y$');
    }],
    'T20' => ['Characterization Snapshot', function() {
        return file_exists(__DIR__ . '/../uji/tests/Tickets/Tkt20Test.php');
    }],
    'T21' => ['Mock Shipping API Client', function() {
        return class_exists('App\Services\MockShippingApiClient') || class_exists('Kunci\MockShippingApiClient');
    }],
    'T22' => ['Architecture Rules (Pest)', function() {
        $code = file_get_contents(__DIR__ . '/../app/Controllers/OrderController.php');
        return !str_contains($code, 'new PDO(');
    }],
    'T23' => ['Eliminasi Dead Routes', function() {
        $code = file_get_contents(__DIR__ . '/../app/public/index.php');
        return !str_contains($code, '/promo/harbolnas-2021');
    }],
    'T24' => ['Phone Cleansing E.164', function() {
        $users = Database::query("SELECT phone FROM users WHERE phone LIKE '08%'");
        return empty($users);
    }],
    'T25' => ['PHPStan Type Safety Helper', function() {
        return class_exists('App\Support\Helpers') || class_exists('Kunci\Helpers');
    }],
    'B01' => ['Circuit Breaker Pattern', function() {
        return class_exists('App\Services\Payment\CircuitBreaker') || class_exists('Kunci\CircuitBreaker');
    }],
    'B02' => ['Transactional Outbox Pattern', function() {
        $t = Database::query("SHOW TABLES LIKE 'outbox_events'");
        return !empty($t);
    }],
    'B03' => ['Automated Rector AST Rule', function() {
        return file_exists(__DIR__ . '/../rector.php');
    }],
    'B04' => ['Mutation Testing Infection', function() {
        return file_exists(__DIR__ . '/../infection.json');
    }],
    'B05' => ['Read-Write Splitting DB', function() {
        return method_exists(Database::class, 'getReadConnection');
    }],
];

// Helper: Kirim telemetri senyap ke server mentor
function kirimTelemetriSenyap($baseUrl, $payload) {
    $urls = [
        rtrim($baseUrl, '/') . '/api/lapor-tiket',
        'http://web/papan/api/lapor-tiket',
        'http://127.0.0.1:8080/papan/api/lapor-tiket'
    ];

    $json = json_encode($payload);
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\nContent-Length: " . strlen($json) . "\r\n",
            'content' => $json,
            'timeout' => 1.5,
            'ignore_errors' => true
        ]
    ]);

    foreach (array_unique($urls) as $targetUrl) {
        try {
            $resp = @file_get_contents($targetUrl, false, $ctx);
            if ($resp !== false) {
                return true;
            }
        } catch (\Throwable $e) {
            // Abaikan error jaringan agar tidak mengganggu kandidat
        }
    }
    return false;
}

// 3. Logika Penanganan CLI
$arg = isset($argv[1]) ? trim($argv[1]) : '';

// Mode A: Evaluasi Lengkap Internal (Dipanggil oleh export_hasil.php atau dashboard mentor)
if ($arg === '--all' || $arg === '--json') {
    $detail = [];
    $totalLulus = 0;
    $totalPoin = 0;

    foreach ($allCheckers as $kode => [$judul, $checker]) {
        $isLulus = false;
        try {
            $isLulus = (bool)$checker();
        } catch (\Throwable $e) {
            $isLulus = false;
        }

        $detail[$kode] = $isLulus ? 'LULUS' : 'GAGAL';
        if ($isLulus) {
            $totalLulus++;
            $totalPoin += str_starts_with($kode, 'B') ? 30 : 10;
        }
    }

    $result = [
        'peserta' => $namaPeserta,
        'meja' => $nomorMeja,
        'role_target' => 'Junior / Mid Backend Engineer',
        'tiket_lulus' => $totalLulus,
        'total_poin' => $totalPoin,
        'waktu' => date('c'),
        'detail' => $detail
    ];

    if ($arg === '--json') {
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
    return $result;
}

// Mode B: Pengujian Spesifik Tiket (Contoh: .\gate.bat uji T04)
$targetKode = strtoupper($arg);
if (!empty($targetKode) && isset($allCheckers[$targetKode])) {
    [$judul, $checker] = $allCheckers[$targetKode];

    $isLulus = false;
    $pesanError = '';
    try {
        $isLulus = (bool)$checker();
    } catch (\Throwable $e) {
        $isLulus = false;
        $pesanError = $e->getMessage();
    }

    $statusStr = $isLulus ? 'LULUS' : 'BELUM MEMENUHI SPESIFIKASI';
    $statusWarna = $isLulus ? "\033[32m[LULUS]\033[0m" : "\033[31m[BELUM MEMENUHI SPESIFIKASI]\033[0m";

    echo "======================================================================\n";
    echo "              HASIL PENGUJIAN TIKET MANDIRI: $targetKode               \n";
    echo "======================================================================\n";
    echo "Kandidat   : $namaPeserta ($nomorMeja)\n";
    echo "Tiket Uji  : $targetKode\n";
    echo "Hasil Uji  : $statusWarna\n";
    if ($isLulus) {
        echo "Keterangan : Perubahan kode Anda berhasil memverifikasi tiket ini!\n";
    } else {
        echo "Keterangan : Logika kode belum memenuhi spesifikasi tiket ini.\n";
        echo "             Silakan telusuri implementasi kode dan coba lagi.\n";
    }
    echo "======================================================================\n";

    // Kirim telemetri otomatis ke server dashboard mentor di latar belakang
    kirimTelemetriSenyap($mentorUrl, [
        'peserta' => $namaPeserta,
        'meja'    => $nomorMeja,
        'tiket'   => $targetKode,
        'status'  => $isLulus ? 'LULUS' : 'GAGAL'
    ]);
    exit($isLulus ? 0 : 1);
}

// Mode C: Status / Bantuan Umum (Tanpa Membocorkan Total Tiket & Poin)
echo "======================================================================\n";
echo "              PAPAN PENGUJIAN TIKET MANDIRI (BLIND MODE)              \n";
echo "======================================================================\n";
echo "Kandidat : $namaPeserta ($nomorMeja)\n";
echo "Sistem   : E-Commerce TokoLaris Legacy Monolith\n";
echo "----------------------------------------------------------------------\n";
echo "Gunakan perintah berikut untuk menguji tiket yang sedang Anda kerjakan:\n";
echo "  .\\gate.bat uji [KODE_TIKET]     (Contoh: .\\gate.bat uji T01)\n\n";
echo "Hasil pengujian tiket akan diverifikasi secara instan dan disinkronkan\n";
echo "secara otomatis ke monitor dashboard mentor di latar belakang.\n";
echo "======================================================================\n";
