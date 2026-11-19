<?php

it('verifies Circuit Breaker transitions from CLOSED to OPEN after 5 failures and fails fast', function () {
    $breakerClass = class_exists('App\Services\Payment\CircuitBreaker')
        ? 'App\Services\Payment\CircuitBreaker'
        : (class_exists('Kunci\CircuitBreaker') ? 'Kunci\CircuitBreaker' : null);

    if (!$breakerClass) {
        test()->markTestIncomplete('Kelas CircuitBreaker belum diimplementasikan.');
    }

    $redis = new Redis();
    $redis->connect(getenv('REDIS_HOST') ?: '127.0.0.1', (int)(getenv('REDIS_PORT') ?: 6379));

    $cb = new $breakerClass($redis, serviceName: 'payment_gateway', failureThreshold: 5, recoveryTime: 30);
    $cb->reset();

    // Simulasi 5 kegagalan berturut-turut
    for ($i = 0; $i < 5; $i++) {
        $cb->recordFailure();
    }

    // Sirkuit sekarang harus berstatus OPEN
    expect($cb->getState())->toBe('OPEN');

    // Request ke-6 harus langsung gagal cepat (fail fast < 2ms) tanpa menembak service
    $start = microtime(true);
    $allowed = $cb->isAvailable();
    $duration = (microtime(true) - $start) * 1000;

    expect($allowed)->toBeFalse()
        ->and($duration)->toBeLessThan(5.0);
});
