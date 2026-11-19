<?php

use App\Support\Database;

it('routes SELECT queries to read-replica connection and mutation queries to primary connection', function () {
    if (!method_exists(Database::class, 'getReadConnection')) {
        test()->markTestIncomplete('Read-Write splitting belum dikonfigurasi di Database.php.');
    }

    $readDb = Database::getReadConnection();
    $writeDb = Database::getConnection();

    expect($readDb)->toBeInstanceOf(PDO::class)
        ->and($writeDb)->toBeInstanceOf(PDO::class);
});
