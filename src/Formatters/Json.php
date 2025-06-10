<?php

namespace Differ\Formatters\Json;

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

        $callback = function ($acc, $item) use ($iter, $depth, $replacer, $spacesCount) {
            $beginIndent = str_repeat($replacer, $depth * $spacesCount);
            $indent = str_repeat($replacer, $depth * $spacesCount + $spacesCount);
            $key = '"key": ';
            $keyValue = $key . toString($item['key']) . ",";
            $begin = $beginIndent . "{";
            $end = $beginIndent . "},";
            switch ($item['type']) {
                case 'add':
                    $type = '"type": "added",';
                    $typeString = sprintf('%s%s', $indent, $type);
                    $keyString = sprintf('%s%s', $indent, $keyValue);
                    if (is_array($item['value'])) {
                        $valueStart = '"value": [';
                        $value = $iter(($item['value']), $depth + 2);
                        $count = count($value);
                        $res = array_slice($value, 0, $count - 1);
                        $lastElement = end($value);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $newValue = [...$res, $trimmedLastElement];
                        $valueEnd = "]";
                        $valueString1 = sprintf('%s%s', $indent, $valueStart);
                        $valueString2 = sprintf('%s%s', $indent, $valueEnd);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $valueString1,
                            ...$newValue,
                            $valueString2,
                            $end
                        ];
                    } else {
                        $value = '"value": ' . toString($item['value']);
                        $valueString = sprintf('%s%s', $indent, $value);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $valueString,
                            $end
                        ];
                    }
                case 'delete':
                    $type = '"type": "deleted",';
                    $typeString = sprintf('%s%s', $indent, $type);
                    $keyString = sprintf('%s%s', $indent, $keyValue);
                    if (is_array($item['value'])) {
                        $valueStart = '"value": [';
                        $value = $iter(($item['value']), $depth + 2);
                        $count = count($value);
                        $res = array_slice($value, 0, $count - 1);
                        $lastElement = end($value);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $newValue = [...$res, $trimmedLastElement];
                        $valueEnd = "]";
                        $valueString1 = sprintf('%s%s', $indent, $valueStart);
                        $valueString2 = sprintf('%s%s', $indent, $valueEnd);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $valueString1,
                            ...$newValue,
                            $valueString2,
                            $end
                        ];
                    } else {
                        $value = '"value": ' . toString($item['value']);
                        $valueString = sprintf('%s%s', $indent, $value);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $valueString,
                            $end
                        ];
                    }
                case 'nested':
                    $type = '"type": "nested",';
                    $typeString = sprintf('%s%s', $indent, $type);
                    $keyString = sprintf('%s%s', $indent, $keyValue);
                    $valueStart = '"children": [';
                    $value = $iter(($item['children']), $depth + 2);
                    $count = count($value);
                    $res = array_slice($value, 0, $count - 1);
                    $lastElement = end($value);
                    $trimmedLastElement = rtrim($lastElement, ',');
                    $newValue = [...$res, $trimmedLastElement];
                    $valueEnd = "]";
                    $valueString1 = sprintf('%s%s', $indent, $valueStart);
                    $valueString2 = sprintf('%s%s', $indent, $valueEnd);
                    return [
                        ...$acc,
                        $begin,
                        $typeString,
                        $keyString,
                        $valueString1,
                        ...$newValue,
                        $valueString2,
                        $end
                    ];
                case 'unchange':
                    $type = '"type": "unchanged",';
                    $typeString = sprintf('%s%s', $indent, $type);
                    $keyString = sprintf('%s%s', $indent, $keyValue);
                    $value = '"value": ' . toString($item['value']);
                    $valueString = sprintf('%s%s', $indent, $value);
                    return [
                        ...$acc,
                        $begin,
                        $typeString,
                        $keyString,
                        $valueString,
                        $end
                    ];
                case 'change':
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    $type = '"type": "changed",';
                    $typeString = sprintf('%s%s', $indent, $type);
                    $keyString = sprintf('%s%s', $indent, $keyValue);
                    if (is_array($oldValue)) {
                        $oldValueStart = '"old value": [';
                        $valueOld = $iter(($oldValue), $depth + 2);
                        $count = count($valueOld);
                        $res = array_slice($valueOld, 0, $count - 1);
                        $lastElement = end($valueOld);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $valueOld1 = [...$res, $trimmedLastElement];
                        $oldValueEnd = "],";
                        $oldValueString1 = sprintf('%s%s', $indent, $oldValueStart);
                        $oldValueString2 = sprintf('%s%s', $indent, $oldValueEnd);
                        $valueNew = '"new value": ' . toString($newValue);
                        $newValueString = sprintf('%s%s', $indent, $valueNew);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $oldValueString1,
                            ...$valueOld1,
                            $oldValueString2,
                            $newValueString,
                            $end
                        ];
                    } elseif (is_array($newValue)) {
                        $valueOld = '"old value": ' . toString($oldValue) . ",";
                        $oldValueString = sprintf('%s%s', $indent, $valueOld);
                        $newValueStart = '"new value": [';
                        $valueNew = $iter(($newValue), $depth + 2);
                        $count = count($valueNew);
                        $res = array_slice($valueNew, 0, $count - 1);
                        $lastElement = end($valueNew);
                        $trimmedLastElement = rtrim($lastElement, ',');
                        $valueNew1 = [...$res, $trimmedLastElement];
                        $newValueEnd = "]";
                        $newValueString1 = sprintf('%s%s', $indent, $newValueStart);
                        $newValueString2 = sprintf('%s%s', $indent, $newValueEnd);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $oldValueString,
                            $newValueString1,
                            ...$valueNew1,
                            $newValueString2,
                            $end
                        ];
                    } else {
                        $valueOld = '"old value": ' . toString($oldValue) . ",";
                        $oldValueString = sprintf('%s%s', $indent, $valueOld);
                        $valueNew = '"new value": ' . toString($newValue);
                        $newValueString = sprintf('%s%s', $indent, $valueNew);
                        return [
                            ...$acc,
                            $begin,
                            $typeString,
                            $keyString,
                            $oldValueString,
                            $newValueString,
                            $end
                        ];
                    }
            }
        };
        return array_reduce($currentValue, $callback, []);
    };

    $res = $iter($diff, 1);
    $result = implode("\n", $res);
    return "[\n" . trim($result, ",") . "\n]";
}
