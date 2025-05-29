<?php

require __DIR__ . '/vendor/autoload.php';

use function Differ\Differ\genDiff;

$diff = genDiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json', 'json');
print_r($diff);
