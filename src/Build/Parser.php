<?php

namespace Gendiff\Build\Parser;

use Symfony\Component\Yaml\Yaml;

function parseJson(string $file): array
{
    return json_decode($file, true);
}

function parseYml(string $file): array
{
    return Yaml::parse($file);
}
