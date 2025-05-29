<?php

namespace Formatters\Json;

function toString(mixed $value): string
{
    if ($value === null) {
        return 'null';
    }
    if (!is_string($value)) {
        return trim(var_export($value, true), "'");
    }
    return '"' . $value . '"';
}

function json(array $diff): string
{
    $replacer = ' ';
    $spacesCount = 2;

    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return [toString($currentValue)];
        }

        $lines = '';

        $callback = function ($acc, $item) use ($iter, $depth, $replacer, $spacesCount) {
            $beginIndent = str_repeat($replacer, $depth * $spacesCount);
            $indent = str_repeat($replacer, $depth * $spacesCount + $spacesCount);
            switch ($item['type']) {
                case 'add':
                    if (is_array($item['value'])) {
                        $result1 = $indent . '"type": "added",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"value": [';
                        $result4 = $iter(($item['value']), $depth + 2);
                        $count = count($result4);
                        $res = array_slice($result4, 0, $count - 1);
                        $lastElement = end($result4);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $newResult4 = [...$res, $trimmedLastElement];
                        $result5 = $indent . "]";
                        return [
                            ...$acc,
                            $beginIndent . "{",
                            $result1,
                            $result2,
                            $result3,
                            ...$newResult4,
                            $result5,
                            $beginIndent . "},"
                        ];
                    } else {
                        $result1 = $indent . '"type": "added",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"value": ' . toString($item['value']);
                        return [...$acc, $beginIndent . "{", $result1, $result2, $result3, $beginIndent . "},"];
                    }
                case 'delete':
                    if (is_array($item['value'])) {
                        $result1 = $indent . '"type": "deleted",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"value": [';
                        $result4 = $iter(($item['value']), $depth + 2);
                        $count = count($result4);
                        $res = array_slice($result4, 0, $count - 1);
                        $lastElement = end($result4);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $newResult4 = [...$res, $trimmedLastElement];
                        $result5 = $indent . "]";
                        return [
                            ...$acc,
                            $beginIndent . "{",
                            $result1,
                            $result2,
                            $result3,
                            ...$newResult4,
                            $result5,
                            $beginIndent . "},"
                        ];
                    } else {
                        $result1 = $indent . '"type": "deleted",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"value": ' . toString($item['value']);
                        return [...$acc, $beginIndent . "{", $result1, $result2, $result3, $beginIndent . "},"];
                    }
                case 'nested':
                    $result1 = $indent . '"type": "nested",';
                    $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                    $result3 = $indent . '"children": [';
                    $result4 = $iter(($item['children']), $depth + 2);
                    $count = count($result4);
                    $res = array_slice($result4, 0, $count - 1);
                    $lastElement = end($result4);
                    $trimmedLastElement = rtrim($lastElement, ',');
                    $newResult4 = [...$res, $trimmedLastElement];
                    $result5 = $indent . "]";
                    return [
                        ...$acc,
                        $beginIndent . "{",
                        $result1,
                        $result2,
                        $result3,
                        ...$newResult4,
                        $result5,
                        $beginIndent . "},"
                    ];
                case 'unchange':
                    $result1 = $indent . '"type": "unchanged",';
                    $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                    $result3 = $indent . '"value": ' . toString($item['value']);
                    return [...$acc, $beginIndent . "{", $result1, $result2, $result3, $beginIndent . "},"];
                case 'change':
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    if (is_array($oldValue)) {
                        $result1 = $indent . '"type": "changed",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"old value": [';
                        $result4 = $iter($oldValue, $depth + 2);
                        $count = count($result4);
                        $res = array_slice($result4, 0, $count - 1);
                        $lastElement = end($result4);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $newResult4 = [...$res, $trimmedLastElement];
                        $result5 = $indent . "],";
                        $result6 = $indent . '"new value": ' . toString($newValue);
                        return [
                            ...$acc,
                            $beginIndent . "{",
                            $result1,
                            $result2,
                            $result3,
                            ...$newResult4,
                            $result5,
                            $result6,
                            $beginIndent . "},"
                        ];
                    } elseif (is_array($newValue)) {
                        $result1 = $indent . '"type": "changed",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"old value": ' . toString($oldValue);
                        $result4 = $indent . '"new value": [';
                        $result5 = $iter($newValue, $depth + 2);
                        $count = count($result5);
                        $res = array_slice($result5, 0, $count - 1);
                        $lastElement = end($result5);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $newResult5 = [...$res, $trimmedLastElement];
                        $result6 = $indent . "],";
                        return [
                            ...$acc,
                            $beginIndent . "{",
                            $result1,
                            $result2,
                            $result3,
                            $result4,
                            ...$newResult5,
                            $result6,
                            $beginIndent . "},"
                        ];
                    } else {
                        $result1 = $indent . '"type": "changed",';
                        $result2 = $indent . '"key": ' . toString($item['key']) . ",";
                        $result3 = $indent . '"old value": ' . toString($oldValue) . ",";
                        $result4 = $indent . '"new value": ' . toString($newValue);
                        return [
                            ...$acc,
                            $beginIndent . "{",
                            $result1,
                            $result2,
                            $result3,
                            $result4,
                            $beginIndent . "},"
                        ];
                    }
            }
        };
        $json = array_reduce($currentValue, $callback, []);
        return $json;
    };
    $res = $iter($diff, 1);
    $result = implode("\n", $res);
    return "[\n" . trim($result, ",") . "\n]";
}
