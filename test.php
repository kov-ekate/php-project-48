<?php

namespace TestPhp;
require_once 'vendor/autoload.php';
use function Differ\Differ\genDiff;

var_dump(genDiff('./file1.yaml', './file2.yaml'));