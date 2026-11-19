<?php

use App\Support\Database;

it('verifies column tracking_code exists in orders table and is indexed (expand-contract)', function () {
    $columns = Database::query("SHOW COLUMNS FROM orders LIKE 'tracking_code'");
    expect($columns)->not->toBeEmpty();

    $indexes = Database::query("SHOW INDEX FROM orders WHERE Column_name = 'tracking_code'");
    expect($indexes)->not->toBeEmpty();
});
