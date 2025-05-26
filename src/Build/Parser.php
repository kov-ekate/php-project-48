<?php

namespace Build\Parser;

use Symfony\Component\Yaml\Yaml;

function parseJson(string $filePath)
{
    $file = file_get_contents($filePath);
    return json_decode($file, true);
}

function parseYml(string $filePath)
{
    $file = Yaml::parseFile($filePath);
    return $file;
}
