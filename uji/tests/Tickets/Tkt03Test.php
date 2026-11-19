<?php

use App\Controllers\OrderController;
use App\Support\Database;

it('prohibits cancelling an order that is already DELIVERED (422 Unprocessable Entity)', function () {
    $controller = new OrderController();

    // Pastikan order id 1 statusnya DELIVERED
    Database::execute("UPDATE orders SET status = 'DELIVERED' WHERE id = 1");

    $response = $controller->cancelOrder(1);

    expect($response['code'])->toBe(422)
        ->and($response['status'])->toBe('error')
        ->and($response['message'])->toContain('tidak dapat dibatalkan');

    // Invariant: Status di database tidak boleh berubah
    $order = Database::query("SELECT status FROM orders WHERE id = 1")[0];
    expect($order['status'])->toBe('DELIVERED');
});

it('prohibits cancelling an order that is already CANCELLED (422 Unprocessable Entity)', function () {
    $controller = new OrderController();

    Database::execute("UPDATE orders SET status = 'CANCELLED' WHERE id = 2");

    $response = $controller->cancelOrder(2);

    expect($response['code'])->toBe(422)
        ->and($response['status'])->toBe('error');
});

it('allows cancelling an order that has PAID or PENDING status', function () {
    $controller = new OrderController();

    Database::execute("UPDATE orders SET status = 'PAID' WHERE id = 3");

    $response = $controller->cancelOrder(3);

    expect($response['code'])->toBe(200)
        ->and($response['status'])->toBe('success');

    $order = Database::query("SELECT status FROM orders WHERE id = 3")[0];
    expect($order['status'])->toBe('CANCELLED');
});
