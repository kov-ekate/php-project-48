<?php

namespace Parse;

function parseJson(string $filePath)
{
    $file = file_get_contents($filePath);
    $data = json_decode($file);
    print_r($data);
    return $data;
}
