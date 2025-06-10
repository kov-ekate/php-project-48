<?php

namespace Differ\Build\Reader;

use Exception;

function readFile(string $pathToFile): string
{
    $data = file_get_contents($pathToFile);
    if ($data === false) {
        throw new Exception("Failed to read file: {$pathToFile}");
    }
    return $data;
}

function getFormat(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}
