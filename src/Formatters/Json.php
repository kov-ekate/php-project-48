<?php

namespace Differ\Formatters\Json;

use function Functional\retry;

function format(array $diff): string
{
    $result = json_encode($diff, JSON_PRETTY_PRINT);

    if ($result === false) {
        return "Error: " . json_last_error_msg();
    }

    return $result;
}
