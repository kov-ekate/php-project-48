<?php

namespace Build\Builder;

use function Build\Parser\parseJson;
use function Build\Parser\parseYml;

function formatValue($value)
{
    if (is_string($value)) {
        return $value;
    } else {
        return json_encode($value);
    }
}

function genArray($file1, $file2)
{
    $keys1 = array_keys((array)$file1);
    $keys2 = array_keys((array)$file2);

    $allKeys = array_unique(array_merge($keys1, $keys2));
    sort($allKeys);

    $diff = [];

    foreach ($allKeys as $key) {
        if (!array_key_exists($key, $file1)) {
            $value = $file2[$key];
            if (is_array($value)) {
                $diff[] = [
                    "type" => "add", 
                    "key" => $key, 
                    "value" => genArray($value, $value)
                ];
            } else {
                $diff[] = [
                    "type" => "add", 
                    "key" => $key, 
                    "value" => $value
                ];
            }
        } elseif (!array_key_exists($key, $file2)) {
            $value = $file1[$key];
            if (is_array($value)) {
                $diff[] = [
                    "type" => "delete", 
                    "key" => $key, 
                    "value" => genArray($value, $value)
                ];
            } else {
                $diff[] = [
                    "type" => "delete", 
                    "key" => $key, 
                    "value" => $value
                ];
            }
        } else {
            $value1 = $file1[$key];
            $value2 = $file2[$key];
            if (is_array($value1) && is_array($value2)) {
                $diff[] = [
                    "type" => "nested", 
                    "key" => $key, 
                    "value" => genArray($value1, $value2)
            ];
            } elseif ($value1 === $value2) {
                $diff[] = [
                    "type" => "unchange", 
                    "key" => $key, 
                    "value" => $value1
                ];
            } else {
                if (is_array($value1)) {
                    $diff[] = [
                        "type" => "change", 
                        "key" => $key, 
                        "old value" => genArray($value1, $value1), 
                        "new value" => $value2
                    ];
                } elseif (is_array($value2)) {
                    $diff[] = [
                        "type" => "change", 
                        "key" => $key, 
                        "new value" => genArray($value2, $value2), 
                        "old value" => $value1
                    ];
                } else {
                $diff[] = [
                    "type" => "change", 
                    "key" => $key, 
                    "old value" => $value1, 
                    "new value" => $value2
                ];
                }
            }
        }
    }
    return $diff; 
}

function buildDiff($pathToFile1, $pathToFile2)
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

    return genArray($file1, $file2);
}
