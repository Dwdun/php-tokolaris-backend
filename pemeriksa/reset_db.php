<?php
// pemeriksa/reset_db.php
// Mereset ulang seluruh tabel & data transaksi awal ke kondisi legacy awal

require_once __DIR__ . '/../app/Support/Database.php';

use App\Support\Database;

echo "Mereset skema dan data database TokoLaris...\n";

try {
    $pdo = Database::getConnection();

    // Disable foreign key checks momentarily for clean reset
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $tables = ['invoices', 'order_items', 'orders', 'products', 'users', 'outbox_events'];
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `{$table}`;");
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    $files = [
        __DIR__ . '/../db/01_skema.sql',
        __DIR__ . '/../data/10_master.sql',
        __DIR__ . '/../data/11_transaksi.sql',
        __DIR__ . '/../data/12_cacat_stok.sql',
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $pdo->exec($sql);
            echo "[✓] Berhasil mengeksekusi " . basename($file) . "\n";
        }
    }

    echo "✅ Database TokoLaris berhasil dikembalikan ke status awal bawaan (Fresh State)!\n";
} catch (\Throwable $e) {
    echo "❌ Gagal mereset database: " . $e->getMessage() . "\n";
    exit(1);
}
