<?php

require __DIR__ . '/vendor/autoload.php';

use function Differ\Differ\genDiff;

$result = genDiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json');
var_dump($result);