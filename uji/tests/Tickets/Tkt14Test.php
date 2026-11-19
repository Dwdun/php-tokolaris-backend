<?php

it('pushes email notification to Redis queue list asynchronously', function () {
    $dtoClass = class_exists('App\DTO\OrderNotificationDTO')
        ? 'App\DTO\OrderNotificationDTO'
        : (class_exists('Kunci\OrderNotificationDTO') ? 'Kunci\OrderNotificationDTO' : null);

    $producerClass = class_exists('App\Services\EmailQueueProducer')
        ? 'App\Services\EmailQueueProducer'
        : (class_exists('Kunci\EmailQueueProducer') ? 'Kunci\EmailQueueProducer' : null);

    expect($dtoClass)->not->toBeNull()
        ->and($producerClass)->not->toBeNull();

    $redis = new Redis();
    $redis->connect(getenv('REDIS_HOST') ?: '127.0.0.1', (int)(getenv('REDIS_PORT') ?: 6379));
    $redis->del('queue:emails');

    $producer = new $producerClass($redis);
    $dto = $dtoClass::fromArray([
        'order_id' => 55,
        'email' => 'buyer@example.com',
        'name' => 'Buyer 55',
        'total_amount' => 50000.0,
        'items' => []
    ]);

    $pushed = $producer->push($dto);
    expect($pushed)->toBeTrue()
        ->and($redis->lLen('queue:emails'))->toBeGreaterThanOrEqual(1);

    $job = json_decode($redis->rPop('queue:emails'), true);
    expect($job['order_id'])->toBe(55)
        ->and($job['email'])->toBe('buyer@example.com');
});
