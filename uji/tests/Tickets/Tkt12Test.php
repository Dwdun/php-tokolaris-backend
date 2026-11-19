<?php

it('prevents duplicate transactions with Redis idempotency guard', function () {
    $guardClass = class_exists('App\Support\IdempotencyGuard')
        ? 'App\Support\IdempotencyGuard'
        : (class_exists('Kunci\IdempotencyGuard') ? 'Kunci\IdempotencyGuard' : null);

    expect($guardClass)->not->toBeNull();

    $redis = new Redis();
    $redis->connect(getenv('REDIS_HOST') ?: '127.0.0.1', (int)(getenv('REDIS_PORT') ?: 6379));

    $guard = new $guardClass($redis);
    $key = 'test_key_' . uniqid();
    $callCount = 0;

    $action = function () use (&$callCount) {
        $callCount++;
        return ['status' => 'success', 'order_id' => 999];
    };

    // Eksekusi pertama: Berhasil
    $res1 = $guard->handle($key, $action);
    expect($res1['status'])->toBe('success')
        ->and($callCount)->toBe(1);

    // Eksekusi kedua dengan key yang sama: Tidak boleh menjalankan $action dua kali!
    $res2 = $guard->handle($key, $action);
    expect($res2['status'])->toBe('success')
        ->and($callCount)->toBe(1); // Tetap 1, membuktikan proteksi idempotensi
});
