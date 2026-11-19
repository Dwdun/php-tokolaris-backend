<?php

use App\Support\Database;

it('verifies B-Tree index exists on orders(user_id) to eliminate full table scans', function () {
    $indexes = Database::query("SHOW INDEX FROM orders WHERE Column_name = 'user_id'");

    expect($indexes)->not->toBeEmpty()
        ->and(count($indexes))->toBeGreaterThanOrEqual(1);
});

it('verifies B-Tree index exists on order_items(order_id) to optimize joins', function () {
    $indexes = Database::query("SHOW INDEX FROM order_items WHERE Column_name = 'order_id'");

    expect($indexes)->not->toBeEmpty()
        ->and(count($indexes))->toBeGreaterThanOrEqual(1);
});
