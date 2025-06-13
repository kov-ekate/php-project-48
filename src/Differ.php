<?php

namespace Differ\Differ;

use function Differ\Build\Builder\buildDiff;
use function Differ\Formatters\chooseFormat;
use function Differ\Build\Reader\readFile;
use function Differ\Build\Reader\getFormat;
use function Differ\Build\Parser\parse;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $data1 = readFile($pathToFile1);
    $format1 = getFormat($pathToFile1);
    $data2 = readFile($pathToFile2);
    $format2 = getFormat($pathToFile2);

    $parseFile1 = parse($data1, $format1);
    $parseFile2 = parse($data2, $format2);

    $diff = buildDiff($parseFile1, $parseFile2);
    return chooseFormat($diff, $format);
}
