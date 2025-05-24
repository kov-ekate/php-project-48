<?php

namespace Parse;

use Symfony\Component\Yaml\Yaml;

function parseJson(string $filePath)
{
    $file = file_get_contents($filePath);
    return json_decode($file);
}

function parseYml(string $filePath)
{
    return Yaml::parseFile($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
}
