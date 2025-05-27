<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testJsonGenDiff(): void
    {
        $pathToJsonFile1 = __DIR__ . "/fixtures/file1.json";
        $pathToJsonFile2 = __DIR__ . "/fixtures/file2.json";

        $resultJson1 = genDiff($pathToJsonFile1, $pathToJsonFile2);
        $expected1 = file_get_contents(__DIR__ . "/fixtures/result-stylish.txt");

        $this->assertEquals($expected1, $resultJson1);

        $resultJson2 = genDiff($pathToJsonFile1, $pathToJsonFile2, 'plain');
        $expected2 = file_get_contents(__DIR__ . "/fixtures/result-plain.txt");

        $this->assertEquals($expected2, $resultJson2);
    }

    public function testYmlGenDiff()
    {
        $pathToYmlFile1 = __DIR__ . "/fixtures/file1.yml";
        $pathToYmlFile2 = __DIR__ . "/fixtures/file2.yml";

        $resultYml1 = genDiff($pathToYmlFile1, $pathToYmlFile2);
        $expected1 = file_get_contents(__DIR__ . "/fixtures/result-stylish.txt");

        $this->assertEquals($expected1, $resultYml1);

        $resultYml2 = genDiff($pathToYmlFile1, $pathToYmlFile2, 'plain');
        $expected2 = file_get_contents(__DIR__ . "/fixtures/result-plain.txt");

        $this->assertEquals($expected2, $resultYml2);
    }

    public function testYamlGenDiff()
    {
        $pathToYamlFile1 = __DIR__ . "/fixtures/file1.yaml";
        $pathToYamlFile2 = __DIR__ . "/fixtures/file2.yaml";

        $resultYaml1 = genDiff($pathToYamlFile1, $pathToYamlFile2);
        $expected1 = file_get_contents(__DIR__ . "/fixtures/result-stylish.txt");

        $this->assertEquals($expected1, $resultYaml1);

        $resultYaml2 = genDiff($pathToYamlFile1, $pathToYamlFile2, 'plain');
        $expected2 = file_get_contents(__DIR__ . "/fixtures/result-plain.txt");

        $this->assertEquals($expected2, $resultYaml2);
    }
}
