<?php

namespace Differ\Formatters\Stylish;

function toString(mixed $value): string
{
    if ($value === null) {
        return 'null';
    }
    return trim(var_export($value, true), "'");
}

function format(array $diff): string
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
            $key = $item['key'];
            switch ($item['type']) {
                case 'added':
                    $sign = '+';
                    if (is_array($item['value'])) {
                        $string = sprintf('%s%s %s: {', $indent, $sign, $key);
                        $value = $iter($item['value'], $depth + 1);
                        return [...$acc, $string, ...$value, $bracketIndent];
                    } else {
                        $value = toString($item['value']);
                        $result = sprintf('%s%s %s: %s', $indent, $sign, $key, $value);
                        return [...$acc, $result];
                    }
                case 'deleted':
                    $sign = '-';
                    if (is_array($item['value'])) {
                        $string = sprintf('%s%s %s: {', $indent, $sign, $key);
                        $value = $iter($item['value'], $depth + 1);
                        return [...$acc, $string, ...$value, $bracketIndent];
                    } else {
                        $value = toString($item['value']);
                        $result = sprintf('%s%s %s: %s', $indent, $sign, $key, $value);
                        return [...$acc, $result];
                    }
                case 'nested':
                    $string = sprintf('%s  %s: {', $indent, $key);
                    $result = $iter($item['children'], $depth + 1);
                    return [...$acc, $string, ...$result, $bracketIndent];
                case 'unchanged':
                    $value = toString($item['value']);
                    $result = sprintf('%s  %s: %s', $indent, $key, $value);
                    return [...$acc, $result];
                case 'changed':
                    $oldValue = $item['old value'];
                    $newValue = $item['new value'];
                    $sign1 = '-';
                    $sign2 = '+';
                    if (is_array($oldValue)) {
                        $value1 = $iter(($oldValue), $depth + 1);
                        $value2 = toString($newValue);
                        $string1 = sprintf('%s%s %s: {', $indent, $sign1, $key);
                        $string2 = sprintf('%s%s %s: %s', $indent, $sign2, $key, $value2);
                        return [...$acc, $string1, ...$value1, $bracketIndent, $string2];
                    } elseif (is_array($newValue)) {
                        $value1 = toString($oldValue);
                        $value2 = $iter(($newValue), $depth + 1);
                        $string1 = sprintf('%s%s %s: %s', $indent, $sign1, $key, $value1);
                        $string2 = sprintf('%s%s %s: {', $indent, $sign2, $key);
                        return [...$acc, $string1, $string2, ...$value2, $bracketIndent];
                    } else {
                        $value1 = toString($oldValue);
                        $value2 = toString($newValue);
                        $result1 = sprintf('%s%s %s: %s', $indent, $sign1, $key, $value1);
                        $result2 = sprintf('%s%s %s: %s', $indent, $sign2, $key, $value2);
                        return [...$acc, $result1, $result2];
                    }
            }
        };
        return array_reduce($currentValue, $callback, []);
    };
    $res = $iter($diff, 1);
    return implode("\n", ['{', ...$res, '}']);
}
