<?php

use App\Controllers\OrderController;

it('fails gracefully when user address is null without throwing PHP fatal error', function () {
    $controller = new OrderController();

    // User id 3 memiliki address NULL di database
    $response = $controller->checkout([
        'user_id' => 3,
        'product_id' => 102,
        'quantity' => 1
    ]);

    expect($response['code'])->toBe(400)
        ->and($response['message'])->toContain('Alamat pengiriman wajib diisi');
});

it('processes checkout successfully when user has a valid address', function () {
    $controller = new OrderController();

    // User id 1 memiliki alamat valid
    $response = $controller->checkout([
        'user_id' => 1,
        'product_id' => 102,
        'quantity' => 1
    ]);

    expect($response['code'])->toBe(200)
        ->and($response['status'])->toBe('success');
});
