<?php

it('dispatches PDF invoice generation job asynchronously without blocking HTTP worker', function () {
    $handlerClass = class_exists('App\Services\AsyncInvoiceHandler')
        ? 'App\Services\AsyncInvoiceHandler'
        : (class_exists('Kunci\AsyncInvoiceHandler') ? 'Kunci\AsyncInvoiceHandler' : null);

    expect($handlerClass)->not->toBeNull();

    $redis = new Redis();
    $redis->connect(getenv('REDIS_HOST') ?: '127.0.0.1', (int)(getenv('REDIS_PORT') ?: 6379));

    $handler = new $handlerClass($redis);
    $start = microtime(true);
    $response = $handler->dispatch(1001);
    $durationMs = (microtime(true) - $start) * 1000;

    // Respon harus cepat < 100ms (tidak boleh sleep(3) di main thread!)
    expect($durationMs)->toBeLessThan(100.0)
        ->and($response['code'])->toBe(202)
        ->and($response['status'])->toBe('accepted')
        ->and($response)->toHaveKey('job_id');
});
