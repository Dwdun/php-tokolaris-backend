<?php

it('provides deterministic shipping rate mock to eliminate flaky tests on external APIs', function () {
    $mockClass = class_exists('App\Services\MockShippingApiClient')
        ? 'App\Services\MockShippingApiClient'
        : (class_exists('Kunci\MockShippingApiClient') ? 'Kunci\MockShippingApiClient' : null);

    expect($mockClass)->not->toBeNull();

    $mock = new $mockClass();
    $rate = $mock->getRate('Jakarta', 'Surabaya', 1200);

    expect($rate)->toBeArray()
        ->and($rate['courier'])->toBe('JNE')
        ->and($rate['cost'])->toBe(18000.0)
        ->and($rate)->toHaveKey('etd');
});
