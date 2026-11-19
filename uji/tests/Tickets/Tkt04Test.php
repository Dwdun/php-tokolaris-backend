<?php

use App\Controllers\OrderController;
use App\Support\Database;

it('rejects order when product stock is already zero at the unit level', function () {
    $controller = new OrderController();

    // Set stock produk 101 sementara ke 0
    Database::execute("UPDATE products SET stock = 0 WHERE id = 101");

    $response = $controller->checkout([
        'user_id' => 1,
        'product_id' => 101,
        'quantity' => 1
    ]);

    expect($response['code'])->toBe(400)
        ->and($response['message'])->toContain('Stok habis');
});

it('prevents overselling and maintains exact zero stock under high concurrency', function () {
    // Reset stok produk 101 ke 10 unit
    Database::execute("UPDATE products SET stock = 10 WHERE id = 101");

    // Jalankan skrip k6 (simulasi 50 user konkuren menembak bersamaan)
    exec('k6 run --quiet load-tests/flash-sale.js 2>&1', $output, $exitCode);

    // Ambil sisa stok di database
    $product = Database::query("SELECT stock FROM products WHERE id = 101")[0];

    // Syarat kelulusan mutlak: Sisa stok harus tepat 0, tidak boleh minus!
    expect((int)$product['stock'])->toBe(0);
});
