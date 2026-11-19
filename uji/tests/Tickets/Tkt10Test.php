<?php

it('calculates 11% PPN tax accurately using Sprout Class without breaking legacy code', function () {
    $class = class_exists('App\Services\TaxCalculatorSprout') 
        ? 'App\Services\TaxCalculatorSprout' 
        : (class_exists('Kunci\TaxCalculatorSprout') ? 'Kunci\TaxCalculatorSprout' : null);

    expect($class)->not->toBeNull();

    $calc = new $class();

    // Subtotal 100.000 -> PPN 11% = 11.000 -> Grand Total = 111.000
    expect($calc->calculateTax(100000))->toBe(11000.0)
        ->and($calc->calculateGrandTotal(100000))->toBe(111000.0);

    // Kasus Tax Exempt (Bebas Pajak)
    expect($calc->calculateTax(100000, isTaxExempt: true))->toBe(0.0)
        ->and($calc->calculateGrandTotal(100000, isTaxExempt: true))->toBe(100000.0);
});
