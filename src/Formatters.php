<?php

namespace Format;

use function Formatters\Stylish\stylish;
use function Formatters\Plain\plain;

function format(array $diff, string $format)
{
    switch ($format) {
        case 'stylish':
            return stylish($diff);
            break;
        case 'plain':
            return plain($diff);
            break;
    }
}
