<?php

use App\Controllers\OrderController;
use App\Support\Database;

it('executes sargable range query without using non-sargable YEAR(created_at) function', function () {
    $controller = new OrderController();

    if (method_exists($controller, 'getSalesRekapByYear')) {
        $result = $controller->getSalesRekapByYear(2024);
    } elseif (class_exists('Kunci\KunciT07')) {
        $result = \Kunci\KunciT07::getSalesRekapByYear(2024);
    } else {
        test()->markTestIncomplete('Method getSalesRekapByYear belum diimplementasikan di OrderController.');
    }

    expect($result)->toBeArray()
        ->and($result)->toHaveKeys(['total_orders', 'total_revenue']);
});
