<?php

require_once __DIR__ . '/vendor/autoload.php';

use function Differ\Differ\genDiff;

$file1 = './file1.json';
$file2 = './file2.json';

$diff = genDiff($file1, $file2);

echo $diff . PHP_EOL;