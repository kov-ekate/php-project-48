<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff() : void
    {
        $pathToFile1 = __DIR__ . "/../fixtures/file1.json";
        $pathToFile2 = __DIR__ . "/../fixtures/file2.json";

        $result = genDiff($pathToFile1, $pathToFile2);
        $expected = file_get_contents(__DIR__ . "/../fixtures/result.txt");

        $this->assertEquals($expected, $result);
    }
}