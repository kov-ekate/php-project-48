<?php

namespace TestPhp;
require_once 'vendor/autoload.php';

use function Differ\Differ\genDiff;
use function Build\Builder\buildDiff;
$diff = genDiff('./file1.json', './file2.json', 'plain');
print_r($diff);