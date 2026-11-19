<?php

use App\Services\CartService;

it('preserves exact legacy cart calculations using characterization golden master', function () {
    $cart = new CartService();
    $items = [
        ['name' => 'Buku Refactoring', 'price' => 150000, 'quantity' => 2],
        ['name' => 'Stiker Developer', 'price' => 25000, 'quantity' => 4]
    ];

    $result = $cart->calculateTotal($items, 'DISKON10');

    // Golden master baseline
    expect($result['subtotal'])->toBe(400000.0)
        ->and($result['discount'])->toBe(40000.0)
        ->and($result['final_total'])->toBe(360000.0);
});
