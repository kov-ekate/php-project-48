<?php

namespace Differ\Differ;

use function Parse\parseJson;
use function Parse\parseYml;

function formatValue($value)
{
    if (is_string($value)) {
        return $value;
    } else {
        return json_encode($value);
    }
}

function genDiff($pathToFile1, $pathToFile2)
{
    $extension1 = pathinfo($pathToFile1, PATHINFO_EXTENSION);

    switch ($extension1) {
        case 'json':
            $file1 = parseJson($pathToFile1);
            $file2 = parseJson($pathToFile2);
            break;
        case 'yml':
            $file1 = parseYml($pathToFile1);
            $file2 = parseYml($pathToFile2);
            break;
        case 'yaml':
            $file1 = parseYml($pathToFile1);
            $file2 = parseYml($pathToFile2);
            break;
    }

    $result = [];

    foreach ($file1 as $key => $value) {
        if (isset($file2->$key)) {
            if ($file2->$key === $value) {
                $result[] = "  {$key}: " . formatValue($value);
            } else {
                $result[] = "- {$key}: " . formatValue($value);
                $result[] = "+ {$key}: " . formatValue($file2->$key);
            }
        } else {
            $result[] = "- {$key}: " . formatValue($value);
        }
    }

    foreach ($file2 as $key => $value) {
        if (!isset($file1->$key)) {
            $result[] = "+ {$key}: " . formatValue($value);
        }
    }

    return implode("\n", $result);
}
