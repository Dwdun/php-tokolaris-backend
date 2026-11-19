<?php

use App\Controllers\SearchController;

it('safely handles malicious SQL injection payloads via parameterized queries', function () {
    $controller = new SearchController();

    // Payload SQL Injection klasik yang berusaha membocorkan seluruh data produk
    $maliciousInput = "' OR '1'='1";
    $results = $controller->search($maliciousInput);

    // Jika query aman berparameter, pencarian ini mencari string literal "' OR '1'='1"
    // sehingga menghasilkan 0 produk (bukan seluruh isi tabel produk)
    expect($results)->toBeArray()
        ->and(count($results))->toBe(0);
});

it('returns correct products for legitimate search queries', function () {
    $controller = new SearchController();
    $results = $controller->search('iPhone');

    expect($results)->toBeArray()
        ->and(count($results))->toBeGreaterThanOrEqual(1)
        ->and($results[0]['name'])->toContain('iPhone');
});
