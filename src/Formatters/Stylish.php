<?php

namespace Differ\Formatters\Stylish;

function toString(mixed $value): string
{
    if ($value === null) {
        return 'null';
    }
    return trim(var_export($value, true), "'");
}

function stylish(array $diff): string
{
    $replacer = ' ';
    $spacesCount = 4;
    $offset = 2;

    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount, $offset) {
        if (!is_array($currentValue)) {
            return [toString($currentValue)];
        }

        $callback = function ($acc, $item) use ($iter, $depth, $replacer, $spacesCount, $offset) {
            $indentSize = $depth * $spacesCount - $offset;
            $indent = str_repeat($replacer, $indentSize);
            $bracketIndent = str_repeat($replacer, $spacesCount * $depth) . "}";
            switch ($item['type']) {
                case 'add':
                    $value = $item['value'];
                    if (is_array($value)) {
                        $string = $indent . "+ {$item['key']}: {";
                        $result = $iter(($item['value']), $depth + 1);
                        return [...$acc, $string, ...$result, $bracketIndent];
                    } else {
                        $result = $indent . "+ {$item['key']}: " . toString($item['value']);
                        return [...$acc, $result];
                    }
                case 'delete':
                    $value = $item['value'];
                    if (is_array($value)) {
                        $string = $indent . "- {$item['key']}: {";
                        $result = $iter(($item['value']), $depth + 1);
                        return [...$acc, $string, ...$result, $bracketIndent];
                    } else {
                        $result = $indent . "- {$item['key']}: " . toString($item['value']);
                        return [...$acc, $result];
                    }
                case 'nested':
                    $string = $indent . "  {$item['key']}: {";
                    $result = $iter($item['children'], $depth + 1);
                    return [...$acc, $string, ...$result, $bracketIndent];
                case 'unchange':
                    $result = $indent . "  {$item['key']}: " . toString($item['value']);
                    return [...$acc, $result];
                case 'change':
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    if (is_array($oldValue)) {
                        $string = $indent . "- {$item['key']}: {";
                        $result1 = $iter(($oldValue), $depth + 1);
                        $result2 = $indent . "+ {$item['key']}: " . toString($newValue);
                        return [...$acc, $string, ...$result1, $bracketIndent, $result2];
                    } elseif (is_array($newValue)) {
                        $string = $indent . "- {$item['key']}: " . toString($oldValue);
                        $result1 = $indent . "+ {$item['key']}: {";
                        $result2 = $iter(($newValue), $depth + 1);
                        return [...$acc, $string, $result1, ...$result2, $bracketIndent];
                    } else {
                        $result1 = $indent . "- {$item['key']}: " . toString($oldValue);
                        $result2 = $indent . "+ {$item['key']}: " . toString($newValue);
                        return [...$acc, $result1, $result2];
                    }
            }
        };
        return array_reduce($currentValue, $callback, []);
    };
    $res = $iter($diff, 1);
    $result = implode("\n", $res);
    return "{\n" . $result . "\n}";
}
