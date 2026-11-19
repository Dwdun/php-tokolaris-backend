<?php

it('enforces architecture separation of concerns (controllers should not contain direct raw PDO)', function () {
    $controllerCode = file_get_contents(__DIR__ . '/../../../app/Controllers/OrderController.php');

    // Architecture invariant: Controller tidak boleh mengeksekusi new PDO() langsung
    expect($controllerCode)->not->toContain('new PDO(');
});
