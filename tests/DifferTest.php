<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Formatting\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testJsonGenDiff(): void
    {
        $pathToJsonFile1 = __DIR__ . "/fixtures/file1.json";
        $pathToJsonFile2 = __DIR__ . "/fixtures/file2.json";

        $resultJson = genDiff($pathToJsonFile1, $pathToJsonFile2);
        $expected = file_get_contents(__DIR__ . "/fixtures/result.txt");

        $this->assertEquals($expected, $resultJson);
    }

    public function testYmlGenDiff()
    {
        $pathToYmlFile1 = __DIR__ . "/fixtures/file1.yml";
        $pathToYmlFile2 = __DIR__ . "/fixtures/file2.yml";

        $resultYml = genDiff($pathToYmlFile1, $pathToYmlFile2);
        $expected = file_get_contents(__DIR__ . "/fixtures/result.txt");

        $this->assertEquals($expected, $resultYml);
    }

    public function testYamlGenDiff()
    {
        $pathToYamlFile1 = __DIR__ . "/fixtures/file1.yaml";
        $pathToYamlFile2 = __DIR__ . "/fixtures/file2.yaml";

        $resultYaml = genDiff($pathToYamlFile1, $pathToYamlFile2);
        $expected = file_get_contents(__DIR__ . "/fixtures/result.txt");

        $this->assertEquals($expected, $resultYaml);
    }
}
