<?php

namespace Differ\Differ;

use function Build\Builder\buildDiff;
use function Format\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $diff = buildDiff($pathToFile1, $pathToFile2);
    return format($diff, $format);
}
