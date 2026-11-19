<?php

it('implements payment adapter pattern adhering to PaymentGatewayInterface contract', function () {
    $interface = interface_exists('App\Services\Payment\PaymentGatewayInterface')
        ? 'App\Services\Payment\PaymentGatewayInterface'
        : (interface_exists('Kunci\PaymentGatewayInterface') ? 'Kunci\PaymentGatewayInterface' : null);

    expect($interface)->not->toBeNull();

    $midtransClass = class_exists('App\Services\Payment\MidtransAdapter')
        ? 'App\Services\Payment\MidtransAdapter'
        : (class_exists('Kunci\MidtransAdapter') ? 'Kunci\MidtransAdapter' : null);

    $xenditClass = class_exists('App\Services\Payment\XenditAdapter')
        ? 'App\Services\Payment\XenditAdapter'
        : (class_exists('Kunci\XenditAdapter') ? 'Kunci\XenditAdapter' : null);

    expect($midtransClass)->not->toBeNull()
        ->and($xenditClass)->not->toBeNull();

    $midtrans = new $midtransClass();
    $xendit = new $xenditClass();

    expect($midtrans)->toBeInstanceOf($interface)
        ->and($xendit)->toBeInstanceOf($interface);

    $resM = $midtrans->charge(50000, 'ORD-123');
    expect($resM['gateway'])->toBe('midtrans')
        ->and($resM)->toHaveKey('va_number');

    $resX = $xendit->charge(50000, 'ORD-123');
    expect($resX['gateway'])->toBe('xendit')
        ->and($resX)->toHaveKey('invoice_url');
});
