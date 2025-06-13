<?php

namespace Differ\Formatters;

use Exception;

function chooseFormat(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            $result = \Differ\Formatters\Stylish\format($diff);
            break;
        case 'plain':
            $result = \Differ\Formatters\Plain\format($diff);
            break;
        case 'json':
            $result = \Differ\Formatters\Json\format($diff);
            break;
        default:
            throw new Exception("Unknown format {$format}");
    }
    return $result;
}
