<?php

namespace Gendiff\Format;

use Exception;

use function Gendiff\Formatters\Stylish\stylish;
use function Gendiff\Formatters\Plain\plain;
use function Gendiff\Formatters\Json\json;

function format(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            $result = stylish($diff);
            break;
        case 'plain':
            $result = plain($diff);
            break;
        case 'json':
            $result = json($diff);
            break;
        default:
            throw new Exception("Unknown format {$format}");
    }
    return $result;
}
