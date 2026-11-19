<?php

use App\Services\CartService;

it('calculates savings ratio without division by zero error when discount is 100%', function () {
    $cart = new CartService();
    $items = [
        ['name' => 'Kopi', 'price' => 50000, 'quantity' => 1]
    ];

    // Voucher GRATIS100 memberikan diskon 100% (final_total = 0)
    $result = $cart->calculateTotal($items, 'GRATIS100');

    expect($result['final_total'])->toBe(0)
        ->and($result['discount'])->toBe(50000)
        ->and($result['savings_ratio'])->toBe(100.0);
});

it('calculates regular discounts accurately', function () {
    $cart = new CartService();
    $items = [
        ['name' => 'Kopi', 'price' => 100000, 'quantity' => 1]
    ];

    $result = $cart->calculateTotal($items, 'DISKON10');

    expect($result['final_total'])->toBe(90000.0)
        ->and($result['discount'])->toBe(10000.0);
});
