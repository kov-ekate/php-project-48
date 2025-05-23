<?php

namespace Parse;

function parseJson(string $filePath)
{
    $file = file_get_contents($filePath);
    return json_decode($file);
}
