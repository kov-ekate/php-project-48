<?php

namespace TestPhp;
require_once 'vendor/autoload.php';

use function Differ\Differ\genDiff;
$diff = genDiff('./file1.yml', './file2.yml', $format = 'plain');
print_r($diff);