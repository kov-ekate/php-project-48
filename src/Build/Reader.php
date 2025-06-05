<?php

namespace Differ\Build\Reader;

use Exception;

function readFile(string $pathToFile): array
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    if ($extension === 'json') {
        $result = [file_get_contents($pathToFile), 'json'];
    } elseif ($extension === 'yml' || $extension === 'yaml') {
        $result = [file_get_contents($pathToFile), 'yaml'];
    } else {
        throw new Exception('Invalid file format');
    }

    if ($result === false) {
        throw new Exception("Failed to read file {$pathToFile}");
    }

    return $result;
}
