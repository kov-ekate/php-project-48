<?php

require __DIR__ . '/vendor/autoload.php';

use function Differ\Differ\genDiff;
use function Build\Builder\buildDiff;

$diff = genDiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json');
var_dump($diff);
