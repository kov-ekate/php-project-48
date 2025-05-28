<?php

namespace Build\Builder;

use function Build\Parser\parseJson;
use function Build\Parser\parseYml;
use function Functional\sort;

function formatValue(mixed $value): string|false
{
    if (is_string($value)) {
        return $value;
    } else {
        return json_encode($value);
    }
}

function genArray(array $file1, array $file2): array
{
    $keys1 = array_keys($file1);
    $keys2 = array_keys($file2);

    $allKeys = array_unique(array_merge($keys1, $keys2));
    $sortKeys = sort($allKeys, fn ($left, $right) => strcmp($left, $right));

    $diff = array_reduce($sortKeys, function ($acc, $key) use ($file1, $file2) {
        if (!array_key_exists($key, $file1)) {
            $value = $file2[$key];
            if (is_array($value)) {
                $acc[] = [
                    "type" => "add",
                    "key" => $key,
                    "value" => genArray($value, $value)
                ];
            } else {
                $acc[] = [
                    "type" => "add",
                    "key" => $key,
                    "value" => $value
                ];
            }
        } elseif (!array_key_exists($key, $file2)) {
            $value = $file1[$key];
            if (is_array($value)) {
                $acc[] = [
                    "type" => "delete",
                    "key" => $key,
                    "value" => genArray($value, $value)
                ];
            } else {
                $acc[] = [
                    "type" => "delete",
                    "key" => $key,
                    "value" => $value
                ];
            }
        } else {
            $value1 = $file1[$key];
            $value2 = $file2[$key];
            if (is_array($value1) && is_array($value2)) {
                $acc[] = [
                    "type" => "nested",
                    "key" => $key,
                    "children" => genArray($value1, $value2)
                ];
            } elseif ($value1 === $value2) {
                $acc[] = [
                    "type" => "unchange",
                    "key" => $key,
                    "value" => $value1
                ];
            } else {
                if (is_array($value1)) {
                    $acc[] = [
                        "type" => "change",
                        "key" => $key,
                        "old value" => genArray($value1, $value1),
                        "new value" => $value2
                    ];
                } elseif (is_array($value2)) {
                    $acc[] = [
                        "type" => "change",
                        "key" => $key,
                        "new value" => genArray($value2, $value2),
                        "old value" => $value1
                    ];
                } else {
                    $acc[] = [
                        "type" => "change",
                        "key" => $key,
                        "old value" => $value1,
                        "new value" => $value2
                    ];
                }
            }
        }
        return $acc;
    }, []);

    return $diff;
}

function buildDiff(string $pathToFile1, string $pathToFile2): array
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
        default:
            return [];
    }

    return genArray($file1, $file2);
}
