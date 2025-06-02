<?php

namespace Format;

use Exception;

use function Formatters\Stylish\stylish;
use function Formatters\Plain\plain;
use function Formatters\Json\json;

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
