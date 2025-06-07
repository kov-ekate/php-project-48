<?php

namespace Differ\Build\Parser;

use Symfony\Component\Yaml\Yaml;
use Exception;

function parseJson(string $data): array
{
    return json_decode($data, true);
}

function parseYml(string $data): array
{
    return Yaml::parse($data);
}

function parse(array $data): array
{
    if ($data[1] === 'json') {
        $parseData = parseJson($data[0]);
    } elseif ($data[1] === 'yaml') {
        $parseData = parseYml($data[0]);
    } else {
        throw new Exception('Invalid file format');
    }
    return $parseData;
}
