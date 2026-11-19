<?php

use App\Controllers\OrderController;

it('fetches 20 orders without triggering the N+1 query problem', function () {
    $controller = new OrderController();
    $result = $controller->listOrders();

    // ❌ Pada kode legacy, $result['total_queries'] bernilai > 40 query!
    // ✅ Kriteria Lulus: Maksimal 3 query SQL (orders, users batch, items batch)
    expect($result['total_queries'])->toBeLessThanOrEqual(3);
});

it('maintains the exact same response structure after optimization', function () {
    $controller = new OrderController();
    $result = $controller->listOrders();

    expect($result['data'])->toBeArray()
        ->and(count($result['data']))->toBeGreaterThan(0)
        ->and($result['data'][0])->toHaveKeys(['id', 'user_id', 'total_amount', 'user', 'items']);
});
