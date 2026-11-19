<?php

use App\Controllers\InvoiceController;

it('prohibits users from accessing invoices belonging to other users (IDOR prevention)', function () {
    $controller = new InvoiceController();

    // Invoice 1002 milik User ID 2
    // User ID 1 mencoba mengunduh invoice milik User ID 2
    $response = $controller->download(invoiceId: 1002, currentUserId: 1);

    // Kriteria Lulus: Harus 403 Forbidden atau 404 Not Found!
    expect($response['code'])->toBeIn([403, 404])
        ->and($response['status'])->toBe('error');
});

it('allows owners to download their own invoice successfully', function () {
    $controller = new InvoiceController();

    // User ID 1 mengunduh invoice 1001 miliknya sendiri
    $response = $controller->download(invoiceId: 1001, currentUserId: 1);

    expect($response['code'])->toBe(200)
        ->and($response['status'])->toBe('success')
        ->and($response['data']['invoice_number'])->toBe('INV-202401-0001');
});
