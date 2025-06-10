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

function parse(string $data, string $format): array
{
    switch ($format) {
        case 'json':
            $parseData = parseJson($data);
            break;
        case 'yml':
            $parseData = parseYml($data);
            break;
        case 'yaml':
            $parseData = parseYml($data);
            break;
        default:
            throw new Exception('Invalid file format');
    }

    return $parseData;
}
