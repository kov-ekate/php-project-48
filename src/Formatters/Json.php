<?php

namespace Formatters\Json;

function toString(mixed $value)
{
    if ($value === null) {
        return 'null';
    }
    if (!is_string($value)) {
        return trim(var_export($value, true), "'");
    }
    return '"' . $value . '"';
}

function json(array $diff)
{
    $replacer = ' ';
    $spacesCount = 2;

    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $lines = '';

        foreach ($currentValue as $item) {
            $beginIndent = str_repeat($replacer, $depth * $spacesCount);
            $indent = str_repeat($replacer, $depth * $spacesCount + $spacesCount);
            switch ($item['type']) {
                case 'add':
                    if (is_array($item['value'])) {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "added"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"value": ' . "[\n" . $iter(($item['value']), $depth + 2);
                        $lines = trim(rtrim($lines), ',');
                        $lines .= "\n" . $indent . "]\n";
                        $lines .= $beginIndent . "},\n";
                    } else {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "added"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"value": ' . toString($item['value']) . "\n";
                        $lines .= $beginIndent . "},\n";
                    }
                    break;
                case 'delete':
                    if (is_array($item['value'])) {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "deleted"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"value": ' . "[\n" . $iter(($item['value']), $depth + 2);
                        $lines = trim(rtrim($lines), ',');
                        $lines .= "\n" . $indent . "]\n";
                        $lines .= $beginIndent . "},\n";
                    } else {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "deleted"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"value": ' . toString($item['value']) . "\n";
                        $lines .= $beginIndent . "},\n";
                    }
                    break;
                case 'nested':
                    $lines .= $beginIndent . "{\n";
                    $lines .= $indent . '"type": "nested"' . ",\n";
                    $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                    $lines .= $indent . '"children": ' . "[\n" . $iter(($item['children']), $depth + 2);
                    $lines = trim(rtrim($lines), ',');
                    $lines .= "\n" . $indent . "]\n";
                    $lines .= $beginIndent . "},\n";
                    break;
                case 'unchange':
                    $lines .= $beginIndent . "{\n";
                    $lines .= $indent . '"type": "unchanged"' . ",\n";
                    $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                    $lines .= $indent . '"value": ' . toString($item['value']) . "\n";
                    $lines .= $beginIndent . "},\n";
                    break;
                case 'change':
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    if (is_array($oldValue)) {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "changed"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"old value": ' . "[\n" . $iter($oldValue, $depth + 2);
                        $lines = trim(rtrim($lines), ',');
                        $lines .= "\n" . $indent . "],\n";
                        $lines .= $indent . '"new value": ' . toString($newValue) . "\n";
                        $lines .= $beginIndent . "},\n";
                    } elseif (is_array($newValue)) {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "changed"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"old value": ' . toString($oldValue) . ",\n";
                        $lines .= $indent . '"new value": ' . "[\n" . $iter($newValue, $depth + 2);
                        $lines = trim(rtrim($lines), ',');
                        $lines .= "\n" . $indent . "]\n";
                        $lines .= $beginIndent . "},\n";
                    } else {
                        $lines .= $beginIndent . "{\n";
                        $lines .= $indent . '"type": "changed"' . ",\n";
                        $lines .= $indent . '"key": ' . toString($item['key']) . ",\n";
                        $lines .= $indent . '"old value": ' . toString($oldValue) . ",\n";
                        $lines .= $indent . '"new value": ' . toString($newValue) . "\n";
                        $lines .= $beginIndent . "},\n";
                    }
                    break;
            }
        }
        return $lines;
    };
    return "[\n" . rtrim($iter($diff, 1), "\n,") . "\n]";
}
