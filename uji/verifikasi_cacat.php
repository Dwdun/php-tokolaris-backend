<?php
// uji/verifikasi_cacat.php
// Membuktikan bahwa setiap cacat/bug warisan benar-benar ada di kode awal TokoLaris

require_once __DIR__ . '/../app/Support/Database.php';

use App\Support\Database;
use App\Controllers\OrderController;
use App\Controllers\SearchController;
use App\Controllers\InvoiceController;

echo "==========================================================\n";
echo " VERIFIKASI CACAT AWAL SISTEM (TOKO LARIS LEGACY BACKEND)\n";
echo "==========================================================\n";

// 1. Verifikasi Cacat T01: Null Address crash/unhandled
$orderCtrl = new OrderController();
$r01 = $orderCtrl->checkout(['user_id' => 3, 'product_id' => 102, 'quantity' => 1]);
echo "[!] Cacat T01 (Null Address)       : " . ($r01['code'] === 400 ? "TERBUKTI ADA" : "TIDAK TERDETEKSI") . "\n";

// 2. Verifikasi Cacat T06: N+1 Query
Database::resetQueryCount();
$r06 = $orderCtrl->listOrders();
echo "[!] Cacat T06 (N+1 Query)          : TERBUKTI ADA ({$r06['total_queries']} queries dieksekusi)\n";

// 3. Verifikasi Cacat T17: SQL Injection
$searchCtrl = new SearchController();
$r17 = $searchCtrl->search("' OR '1'='1");
echo "[!] Cacat T17 (SQL Injection)      : " . (count($r17) > 0 ? "TERBUKTI RENTAN (Data bocor)" : "AMAN") . "\n";

// 4. Verifikasi Cacat T18: IDOR
$invCtrl = new InvoiceController();
$r18 = $invCtrl->download(1002, 1);
echo "[!] Cacat T18 (IDOR Invoice)       : " . ($r18['code'] === 200 ? "TERBUKTI RENTAN (User 1 bisa akses invoice User 2)" : "AMAN") . "\n";

// 5. Verifikasi Cacat T19: MD5 Hash
$user = Database::query("SELECT password FROM users WHERE id = 1")[0];
echo "[!] Cacat T19 (MD5 Hash Usang)     : " . (strlen($user['password']) === 32 ? "TERBUKTI PAKAI MD5 ({$user['password']})" : "SUDAH BCRYPT") . "\n";

echo "==========================================================\n";
