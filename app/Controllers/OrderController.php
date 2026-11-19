<?php

namespace App\Controllers;

use App\Support\Database;
use Exception;

/**
 * OrderController (Legacy Monolithic Fat Controller)
 * Mengandung pola-pola anti-pattern warisan 6 tahun lalu yang perlu direfaktor.
 */
class OrderController {

    /**
     * TKT-01 & TKT-04: Checkout Pemesanan
     * ANTI-PATTERN:
     * 1. Null pointer pada user address (TKT-01)
     * 2. Check-Then-Act di memori PHP yang memicu Race Condition Stok Minus (TKT-04)
     */
    public function checkout(array $payload): array {
        $db = Database::getConnection();
        $userId = $payload['user_id'] ?? null;
        $productId = $payload['product_id'] ?? null;
        $qty = $payload['quantity'] ?? 1;

        // 1. Ambil data user
        $user = Database::query("SELECT * FROM users WHERE id = ?", [$userId])[0] ?? null;
        if (!$user) {
            return ['status' => 'error', 'code' => 404, 'message' => 'User tidak ditemukan'];
        }

        // ❌ BUG TKT-01: Null Pointer Exception jika address NULL
        // Di PHP 8.2 ini bisa memicu error atau logic bypass jika tidak dicek dengan benar
        if (trim((string)$user['address']) === '') {
            return ['status' => 'error', 'code' => 400, 'message' => 'Alamat pengiriman wajib diisi sebelum checkout'];
        }

        // 2. Ambil data produk
        $product = Database::query("SELECT * FROM products WHERE id = ?", [$productId])[0] ?? null;
        if (!$product) {
            return ['status' => 'error', 'code' => 404, 'message' => 'Produk tidak ditemukan'];
        }

        // ❌ BUG FATAL TKT-04: Check-Then-Act di memori aplikasi!
        // Di bawah 50 request konkuren, 38 request membaca `$product['stock'] > 0` bersamaan!
        if ($product['stock'] >= $qty) {
            // Simulasi latensi pemrosesan memori 50 milidetik (memperbesar window race condition)
            usleep(50000); 

            $newStock = $product['stock'] - $qty;
            Database::execute("UPDATE products SET stock = ? WHERE id = ?", [$newStock, $productId]);

            // Buat order baru
            $total = $product['price'] * $qty;
            Database::execute(
                "INSERT INTO orders (user_id, total_amount, status, payment_method) VALUES (?, ?, 'PAID', ?)",
                [$userId, $total, $payload['payment_method'] ?? 'bank_transfer']
            );
            $orderId = (int)$db->lastInsertId();

            return [
                'status' => 'success',
                'code' => 200,
                'data' => [
                    'order_id' => $orderId,
                    'product_name' => $product['name'],
                    'remaining_stock' => $newStock
                ]
            ];
        } else {
            return ['status' => 'error', 'code' => 400, 'message' => 'Stok habis'];
        }
    }

    /**
     * TKT-06: Riwayat Daftar Pesanan
     * ❌ BUG FATAL: N+1 Query Problem!
     * Melakukan query SQL terpisah di dalam loop untuk setiap baris order.
     */
    public function listOrders(): array {
        Database::resetQueryCount();

        // 1 Query awal mengambil 20 order
        $orders = Database::query("SELECT * FROM orders ORDER BY id DESC LIMIT 20");
        $results = [];

        foreach ($orders as $order) {
            // ❌ QUERY N: Mengambil user per order secara terpisah
            $user = Database::query("SELECT id, name, email FROM users WHERE id = ?", [$order['user_id']])[0] ?? null;

            // ❌ QUERY N: Mengambil order items per order secara terpisah
            $items = Database::query(
                "SELECT oi.*, p.name as product_name 
                 FROM order_items oi 
                 JOIN products p ON oi.product_id = p.id 
                 WHERE oi.order_id = ?", 
                [$order['id']]
            );

            $order['user'] = $user;
            $order['items'] = $items;
            $results[] = $order;
        }

        return [
            'total_queries' => Database::getQueryCount(),
            'data' => $results
        ];
    }

    /**
     * TKT-03: Pembatalan Pesanan
     * ❌ BUG: State Machine Bypass
     * Membiarkan status pesanan 'DELIVERED' diubah menjadi 'CANCELLED'.
     */
    public function cancelOrder(int $orderId): array {
        $order = Database::query("SELECT * FROM orders WHERE id = ?", [$orderId])[0] ?? null;
        if (!$order) {
            return ['status' => 'error', 'code' => 404, 'message' => 'Order tidak ditemukan'];
        }

        // ❌ BUG: Tidak ada validasi transisi status state machine!
        Database::execute("UPDATE orders SET status = 'CANCELLED' WHERE id = ?", [$orderId]);

        return ['status' => 'success', 'code' => 200, 'message' => 'Pesanan berhasil dibatalkan'];
    }

    /**
     * TKT-15: Pembuatan PDF Invoice
     * ❌ BUG: Synchronous Blocking HTTP Thread
     */
    public function generateInvoice(int $orderId): array {
        $order = Database::query("SELECT * FROM orders WHERE id = ?", [$orderId])[0] ?? null;
        if (!$order) {
            return ['status' => 'error', 'code' => 404, 'message' => 'Order tidak ditemukan'];
        }

        // Simulasi beban render PDF berat di thread HTTP (blocking 3 detik)
        sleep(3); 

        return [
            'status' => 'success',
            'code' => 200,
            'file_url' => "/invoices/INV-{$orderId}.pdf"
        ];
    }
}
