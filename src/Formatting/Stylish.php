<?php

namespace Formatting\Stylish;

function toString($value)
{
    return trim(var_export($value, true), "'");
}

function stylish(array $diff)
{
    $replacer = ' ';
    $spacesCount = 4;
    $offset = 2;

    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount, $offset) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = $depth * $spacesCount - $offset;
        $bracketIndent = str_repeat($replacer, $spacesCount * $depth);

        $lines = '';

        foreach ($currentValue as $item) {
            $indent = str_repeat($replacer, $indentSize);
            switch ($item['type']) {
                case 'add':
                    $value = $item['value'];
                    if (is_array($value)) {
                        $lines .= $indent . "+ {$item['key']}: {\n" . $iter(($item['value']), $depth + 1);
                        $lines .= $bracketIndent . "}\n";
                    } else {
                        $lines .= $indent . "+ {$item['key']}: " . toString($item['value']) . "\n";
                    }
                    break;
                case 'delete':
                    $value = $item['value'];
                    if (is_array($value)) {
                        $lines .= $indent . "- {$item['key']}: {\n" . $iter(($item['value']), $depth + 1);
                        $lines .= $bracketIndent . "}\n";
                    } else {
                        $lines .= $indent . "- {$item['key']}: " . toString($item['value']) . "\n";
                    }
                    break;
                case 'nested':
                    $lines .= $indent . "  {$item['key']}: {\n";
                    $lines .= $iter($item['value'], $depth + 1);
                    $lines .= $bracketIndent . "}\n";
                    break;
                case 'unchange':
                    $lines .= $indent . "  {$item['key']}: " . toString($item['value']) . "\n";
                    break;
                case 'change':
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    if (is_array($oldValue)) {
                        $lines .= $indent . "- {$item['key']}: {\n" . $iter(($item['old value']), $depth + 1);
                        $lines .= $bracketIndent . "}\n";
                        $lines .= $indent . "+ {$item['key']}: " . toString($item['new value']) . "\n";
                    } elseif (is_array($newValue)) {
                        $lines .= $indent . "- {$item['key']}: " . toString($item['old value']) . "\n";
                        $lines .= $indent . "+ {$item['key']}: {\n" . $iter(($item['new value']), $depth + 1);
                        $lines .= $bracketIndent . "}\n";
                    } else {
                        $lines .= $indent . "- {$item['key']}: " . toString($item['old value']) . "\n";
                        $lines .= $indent . "+ {$item['key']}: " . toString($item['new value']) . "\n"; 
                    }
                    break;
            }
        
        }
        return $lines;
    };
    return "{\n" . $iter($diff, 1) . "}";
}