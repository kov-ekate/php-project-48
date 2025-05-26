<?php

namespace TestPhp;
require_once 'vendor/autoload.php';

use function Formatting\Differ\genDiff;
$diff = genDiff('./file1.yml', './file2.yml');
$output = print_r($diff, true);
echo "<pre>" . htmlspecialchars($output) . "</pre>";