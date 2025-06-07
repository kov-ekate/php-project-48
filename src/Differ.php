<?php

namespace Differ\Differ;

use function Differ\Build\Builder\buildDiff;
use function Differ\Format\format;
use function Differ\Build\Reader\readFile;
use function Differ\Build\Parser\parse;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $file1 = readFile($pathToFile1);
    $file2 = readFile($pathToFile2);

    $parseFile1 = parse($file1);
    $parseFile2 = parse($file2);

    $diff = buildDiff($parseFile1, $parseFile2);
    return format($diff, $format);
}
