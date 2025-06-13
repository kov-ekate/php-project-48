<?php

namespace Differ\Build\Builder;

use function Functional\sort;

function buildDiff(array $data1, array $data2): array
{
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);

    $allKeys = array_unique(array_merge($keys1, $keys2));
    $sortKeys = sort($allKeys, fn ($left, $right) => strcmp($left, $right));

    $diff = array_reduce($sortKeys, function ($acc, $key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $value = is_array($data2[$key]) ? buildDiff($data2[$key], $data2[$key]) : $data2[$key];
            $result = [
                "type" => "added",
                "key" => $key,
                "value" => $value
            ];
            return [...$acc, $result];
        } elseif (!array_key_exists($key, $data2)) {
            $value = is_array($data1[$key]) ? buildDiff($data1[$key], $data1[$key]) : $data1[$key];
            $result = [
                "type" => "deleted",
                "key" => $key,
                "value" => $value
            ];
            return [...$acc, $result];
        } elseif (is_array($data1[$key]) && is_array($data2[$key])) {
            $result = [
                "type" => "nested",
                "key" => $key,
                "children" => buildDiff($data1[$key], $data2[$key])
            ];
            return [...$acc, $result];
        } elseif ($data1[$key] === $data2[$key]) {
            $result = [
                "type" => "unchanged",
                "key" => $key,
                "value" => $data1[$key]
            ];
            return [...$acc, $result];
        } elseif ($data1[$key] !== $data2[$key]) {
            $oldValue = is_array($data1[$key]) ? buildDiff($data1[$key], $data1[$key]) : $data1[$key];
            $newValue = is_array($data2[$key]) ? buildDiff($data2[$key], $data2[$key]) : $data2[$key];
            $result = [
                "type" => "changed",
                "key" => $key,
                "old value" => $oldValue,
                "new value" => $newValue
            ];
            return [...$acc, $result];
        }
    }, []);

    return $diff;
}
