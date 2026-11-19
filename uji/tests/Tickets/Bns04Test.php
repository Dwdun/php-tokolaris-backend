<?php

it('verifies Infection mutation configuration exists and targets min MSI 85', function () {
    $configPath = __DIR__ . '/../../../infection.json';

    if (!file_exists($configPath)) {
        test()->markTestIncomplete('File infection.json belum dibuat di root repository.');
    }

    $json = json_decode(file_get_contents($configPath), true);
    expect($json)->toBeArray()
        ->and($json['minMsi'] ?? 0)->toBeGreaterThanOrEqual(85);
});
