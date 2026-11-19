<?php

use App\Controllers\OrderController;

it('returns backward-compatible flat address alongside modern nested address_detail', function () {
    $sampleOrder = [
        'id' => 101,
        'total_amount' => 250000.0,
        'status' => 'PAID',
        'address' => 'Jl. Sudirman No 45',
        'city' => 'Jakarta Selatan',
        'postal_code' => '12190'
    ];

    if (method_exists(OrderController::class, 'formatOrderResponse')) {
        $formatted = OrderController::formatOrderResponse($sampleOrder);
    } elseif (class_exists('Kunci\KunciT09')) {
        $formatted = \Kunci\KunciT09::formatOrderResponse($sampleOrder);
    } else {
        test()->markTestIncomplete('Format response backward compatible belum dibuat.');
    }

    // Klien lama: Membutuhkan flat string 'address'
    expect($formatted['address'])->toBe('Jl. Sudirman No 45');

    // Klien baru: Membutuhkan nested object 'address_detail'
    expect($formatted['address_detail'])->toBeArray()
        ->and($formatted['address_detail']['raw'])->toBe('Jl. Sudirman No 45')
        ->and($formatted['address_detail']['city'])->toBe('Jakarta Selatan')
        ->and($formatted['address_detail']['postal_code'])->toBe('12190');
});
