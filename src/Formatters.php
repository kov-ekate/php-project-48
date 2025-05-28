<?php

namespace Format;

use function Formatters\Stylish\stylish;
use function Formatters\Plain\plain;
use function Formatters\Json\json;

function format(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return stylish($diff);
            break;
        case 'plain':
            return plain($diff);
            break;
        case 'json':
            return json($diff);
            break;
    }
}
