<?php

namespace Build\Parser;

use Exception;
use Symfony\Component\Yaml\Yaml;

function readFile(string $pathToFile): string
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    if ($extension === 'json') {
        $result = (string)file_get_contents($pathToFile);
    } elseif ($extension === 'yml' || $extension === 'yaml') {
        $result = file_get_contents($pathToFile);
    } else {
        throw new Exception('Invalid file format');
    }

    if ($result === false) {
        throw new Exception("Failed to read file {$pathToFile}");
    }

    return $result;
}

function parseJson(string $file): array
{
    return json_decode($file, true);
}

function parseYml(string $file): array
{
    return Yaml::parse($file);
}
