<?php

namespace Gendiff\Differ\Differ;

use function Gendiff\Build\Builder\buildDiff;
use function Gendiff\Format\format;
use function Gendiff\Build\Reader\readFile;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $file1 = readFile($pathToFile1);
    $file2 = readFile($pathToFile2);
    $diff = buildDiff($file1, $file2);
    return format($diff, $format);
}
