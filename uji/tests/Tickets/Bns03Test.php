<?php

it('verifies Rector configuration file exists and contains PHP 8.2 migration sets', function () {
    $rectorPath = __DIR__ . '/../../../rector.php';

    if (!file_exists($rectorPath)) {
        test()->markTestIncomplete('File rector.php belum dibuat di root repository.');
    }

    $content = file_get_contents($rectorPath);
    expect($content)->toContain('LevelSetList::UP_TO_PHP_82');
});
