<?php

namespace Differ\Build\Reader;

use Exception;

function readFile(string $pathToFile): string
{
    return file_get_contents($pathToFile);
}

function getFormat(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}
