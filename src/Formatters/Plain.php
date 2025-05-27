<?php

namespace Formatters\Plain;

function toString($value)
{
    if ($value === null) {
        return 'null';
    }
    if (!is_string($value)) {
        return trim(var_export($value, true), "'");
    }
    return "'{$value}'";
}

function plain(array $diff)
{

    $iter = function ($currentValue, $parentKey = '') use (&$iter) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $lines = '';

        foreach ($currentValue as $item) {
            $key = $item['key'];
            $currentKey = empty($parentKey) ? $key : "{$parentKey}.{$key}";
            switch ($item['type']) {
                case 'add':
                    $value = $item['value'];
                    if (is_array($value)) {
                        $lines .= "Property '{$currentKey}' was added with value: [complex value]\n";
                    } else {
                        $lines .= "Property '{$currentKey}' was added with value: " . toString($value) . "\n";
                    }
                    break;
                case 'delete':
                    $lines .= "Property '{$currentKey}' was removed\n";
                    break;
                case 'nested':
                    $value = $item['children'];
                    $lines .= $iter($value, $currentKey);
                    break;
                case 'change':
                    $key = $item['key'];
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    if (is_array($oldValue)) {
                        $lines .= "Property '{$currentKey}' was updated. ";
                        $lines .= "From [complex value] to " . toString($newValue) . "\n";
                    } elseif (is_array($newValue)) {
                        $lines .= "Property '{$currentKey}' was updated. ";
                        $lines .= "From " . toString($oldValue) . " to [complex value]\n";
                    } else {
                        $lines .= "Property '{$currentKey}' was updated. ";
                        $lines .= "From " . toString($oldValue) . " to " . toString($newValue) . "\n";
                    }
                    break;
            }
        }
        return $lines;
    };
    return trim($iter($diff));
}
