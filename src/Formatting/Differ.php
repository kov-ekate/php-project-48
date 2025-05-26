<?php

namespace Formatting\Differ;

use function Build\Builder\buildDiff;
use function Formatting\Stylish\stylish;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $diff = buildDiff($pathToFile1, $pathToFile2);
    return format($diff, $format);
}

function format(array $diff, string $format)
{
    switch ($format) {
        case 'stylish':
            return stylish($diff);
            break;
    }
}
