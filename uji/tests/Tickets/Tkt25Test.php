<?php

it('ensures type safety and null safety helpers pass strict static analysis without TypeError', function () {
    $helperClass = class_exists('App\Support\Helpers')
        ? 'App\Support\Helpers'
        : (class_exists('Kunci\Helpers') ? 'Kunci\Helpers' : null);

    expect($helperClass)->not->toBeNull();

    // Null safety: Menerima null tanpa fatal error
    expect($helperClass::formatRupiah(null))->toBe('Rp 0')
        ->and($helperClass::formatRupiah(150000.0))->toBe('Rp 150.000');

    // API Response contract
    $res = $helperClass::apiResponse('success', 200, ['id' => 1]);
    expect($res['code'])->toBe(200)
        ->and($res['status'])->toBe('success');
});
