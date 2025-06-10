<?php

namespace Differ\Formatters\Plain;

function toString(mixed $value): string
{
    if ($value === null) {
        return 'null';
    }
    if (is_array($value)) {
        return "[complex value]";
    }
    if (!is_string($value)) {
        return trim(var_export($value, true), "'");
    }
    return "'{$value}'";
}

function plain(array $diff): string
{

    $iter = function ($currentValue, $parentKey = '') use (&$iter) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $plain = array_reduce($currentValue, function ($acc, $item) use ($iter, $parentKey) {
            $key = $item['key'];
            $currentKey = $parentKey === '' ? $key : "{$parentKey}.{$key}";
            switch ($item['type']) {
                case 'add':
                    $value = toString($item['value']);
                    $result = "Property '{$currentKey}' was added with value: {$value}";
                    return [...$acc, $result];
                case 'delete':
                    $result = "Property '{$currentKey}' was removed";
                    return [...$acc, $result];
                case 'nested':
                    $value = $item['children'];
                    $result = $iter($value, $currentKey);
                    return [...$acc, ...$result];
                case 'change':
                    $oldValue = toString($item['old value']);
                    $newValue = toString($item['new value']);
                    $string1 = "Property '{$currentKey}' was updated. ";
                    $string2 = "From {$oldValue} to {$newValue}";
                    return [...$acc, $string1 . $string2];
            }
                return $acc;
        }, []);
        return $plain;
    };
    $res = $iter($diff);
    return implode("\n", $res);
}
