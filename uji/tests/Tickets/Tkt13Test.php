<?php

it('validates email format and encapsulates order notification data in DTO', function () {
    $dtoClass = class_exists('App\DTO\OrderNotificationDTO')
        ? 'App\DTO\OrderNotificationDTO'
        : (class_exists('Kunci\OrderNotificationDTO') ? 'Kunci\OrderNotificationDTO' : null);

    expect($dtoClass)->not->toBeNull();

    // Data valid
    $validData = [
        'order_id' => 10,
        'email' => 'customer@tokolaris.com',
        'name' => 'Budi Santoso',
        'total_amount' => 150000.0,
        'items' => [['id' => 1, 'name' => 'Kopi']]
    ];

    $dto = $dtoClass::fromArray($validData);
    expect($dto->recipientEmail)->toBe('customer@tokolaris.com')
        ->and($dto->orderId)->toBe(10)
        ->and($dto->toPayload())->toBeArray();

    // Data tidak valid (Email rusak)
    expect(fn() => $dtoClass::fromArray([
        'order_id' => 11,
        'email' => 'bukan-email-valid',
        'name' => 'Budi'
    ]))->toThrow(\InvalidArgumentException::class);
});
