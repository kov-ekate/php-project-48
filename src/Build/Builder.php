<?php

namespace Differ\Build\Builder;

use Exception;

use function Differ\Build\Parser\parseJson;
use function Differ\Build\Parser\parseYml;
use function Functional\sort;

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
                $result = [
                    "type" => "add",
                    "key" => $key,
                    "value" => genArray($value, $value)
                ];
                return [...$acc, $result];
            } else {
                $result = [
                    "type" => "add",
                    "key" => $key,
                    "value" => $value
                ];
                return [...$acc, $result];
            }
        } elseif (!array_key_exists($key, $file2)) {
            $value = $file1[$key];
            if (is_array($value)) {
                $result = [
                    "type" => "delete",
                    "key" => $key,
                    "value" => genArray($value, $value)
                ];
                return [...$acc, $result];
            } else {
                $result = [
                    "type" => "delete",
                    "key" => $key,
                    "value" => $value
                ];
                return [...$acc, $result];
            }
        } else {
            $value1 = $file1[$key];
            $value2 = $file2[$key];
            if (is_array($value1) && is_array($value2)) {
                $result = [
                    "type" => "nested",
                    "key" => $key,
                    "children" => genArray($value1, $value2)
                ];
                return [...$acc, $result];
            } elseif ($value1 === $value2) {
                $result = [
                    "type" => "unchange",
                    "key" => $key,
                    "value" => $value1
                ];
                return [...$acc, $result];
            } else {
                if (is_array($value1)) {
                    $result = [
                        "type" => "change",
                        "key" => $key,
                        "old value" => genArray($value1, $value1),
                        "new value" => $value2
                    ];
                    return [...$acc, $result];
                } elseif (is_array($value2)) {
                    $result = [
                        "type" => "change",
                        "key" => $key,
                        "new value" => genArray($value2, $value2),
                        "old value" => $value1
                    ];
                    return [...$acc, $result];
                } else {
                    $result = [
                        "type" => "change",
                        "key" => $key,
                        "old value" => $value1,
                        "new value" => $value2
                    ];
                    return [...$acc, $result];
                }
            }
        }
    }, []);

    return $diff;
}

function buildDiff(array $file1, array $file2): array
{
    if ($file1[1] === 'json') {
        $parseFile1 = parseJson($file1[0]);
    } elseif ($file1[1] === 'yaml') {
        $parseFile1 = parseYml($file1[0]);
    }

    if ($file2[1] === 'json') {
        $parseFile2 = parseJson($file2[0]);
    } elseif ($file2[1] === 'yaml') {
        $parseFile2 = parseYml($file2[0]);
    }

    return genArray($parseFile1, $parseFile2);
}
