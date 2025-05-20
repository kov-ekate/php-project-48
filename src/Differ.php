<?php

namespace Differ\Differ;

use function Parse\parseJson;

function genDiff($pathToFile1, $pathToFile2)
{
    $file1 = parseJson($pathToFile1);
    $file2 = parseJson($pathToFile2);
    $result = [];

    foreach ($file1 as $key => $value) {
        if (array_key_exists($key, $file2)) {
            if($file2[$key] == $value) {
                $result[] = "{$key}: {$value}";
            } else {
                $result[] = "- {$key}: {$value}";
                $result[] = "+ {$key}: {$file2[$key]}";
            }
        } else {
            $result[] = "- {$key}: {$value}";
        }
    }

    foreach ($file2 as $key => $value) {
        if(!array_key_exists($key, $result)) {
            $result[] = "+ {$key}: {$value}";
        }
    }

    return implode("\n", $result);
}

var_dump(genDiff("../file1.json", "../file2.json"));
