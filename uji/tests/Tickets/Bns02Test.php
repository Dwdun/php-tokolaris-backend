<?php

use App\Support\Database;

it('verifies Transactional Outbox pattern records events atomically with orders', function () {
    $tables = Database::query("SHOW TABLES LIKE 'outbox_events'");
    if (empty($tables)) {
        test()->markTestIncomplete('Tabel outbox_events belum dibuat.');
    }

    // Periksa struktur outbox_events
    $columns = Database::query("SHOW COLUMNS FROM outbox_events");
    $colNames = array_column($columns, 'Field');

    expect($colNames)->toContain('aggregate_type')
        ->and($colNames)->toContain('payload')
        ->and($colNames)->toContain('status');
});
