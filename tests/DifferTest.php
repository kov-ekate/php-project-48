<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    protected function getFixturesName(string $fixtureName): string
    {
        $path = __DIR__ . "/fixtures/";
        return $path . $fixtureName;
    }

    #[DataProvider('genDiffDataProvider')]
    public function testGenDiff(string $file1, string $file2, string $format, string $result): void
    {
        $pathToFile1 = $this->getFixturesName($file1);
        $pathToFile2 = $this->getFixturesName($file2);

        $expected = file_get_contents($this->getFixturesName($result));
        $actual = genDiff($pathToFile1, $pathToFile2, $format);
        $this->assertEquals($expected, $actual);
    }

    public static function genDiffDataProvider(): array
    {
        return [
            [
                'file1.json',
                'file2.json',
                'stylish',
                'result-stylish.txt'
            ],
            [
                'file1.json',
                'file2.json',
                'plain',
                'result-plain.txt'
            ],
            [
                'file1.json',
                'file2.json',
                'json',
                'result-json.txt'
            ],
            [
                'file1.yml',
                'file2.yml',
                'stylish',
                'result-stylish.txt'
            ],
            [
                'file1.yml',
                'file2.yml',
                'plain',
                'result-plain.txt'
            ],
            [
                'file1.yml',
                'file2.yml',
                'json',
                'result-json.txt'
            ],
            [
                'file1.yaml',
                'file2.yaml',
                'stylish',
                'result-stylish.txt'
            ],
            [
                'file1.yaml',
                'file2.yaml',
                'plain',
                'result-plain.txt'
            ],
            [
                'file1.yaml',
                'file2.yaml',
                'json',
                'result-json.txt'
            ]
        ];
    }
}
