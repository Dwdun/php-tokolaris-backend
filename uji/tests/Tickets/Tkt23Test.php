<?php

it('verifies dead routes and legacy beta endpoints are eliminated from router', function () {
    $indexPath = __DIR__ . '/../../../app/public/index.php';
    $code = file_get_contents($indexPath);

    // Endpoint zombie tahun 2021 harus sudah dibersihkan
    expect($code)->not->toContain('/promo/harbolnas-2021')
        ->and($code)->not->toContain('/api/v0/beta-checkout');
});
