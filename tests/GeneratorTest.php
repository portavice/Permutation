<?php

namespace Portavice\Permutation\Tests;

use PHPUnit\Framework\TestCase;
use Portavice\Permutation\Permutation;

class GeneratorTest extends TestCase
{
    public function testPermutationGeneratorFunction()
    {
        $input = [
            'first' => ['a', 'b'],
            'second' => ['w', 'v'],
            'third' => ['y', 'z'],
        ];

        $iterator = Permutation::getGenerator($input);

        $results = [];
        foreach ($iterator as $value) {
            $results[] = $value;
        }

        static::assertEquals([
            ['first' => 'a', 'second' => 'w', 'third' => 'y'],
            ['first' => 'b', 'second' => 'w', 'third' => 'y'],
            ['first' => 'a', 'second' => 'v', 'third' => 'y'],
            ['first' => 'b', 'second' => 'v', 'third' => 'y'],
            ['first' => 'a', 'second' => 'w', 'third' => 'z'],
            ['first' => 'b', 'second' => 'w', 'third' => 'z'],
            ['first' => 'a', 'second' => 'v', 'third' => 'z'],
            ['first' => 'b', 'second' => 'v', 'third' => 'z'],
        ], $results);
    }

    public function testPermutationGeneratorWithCallback()
    {
        $input = [
            'first' => ['a', 'b'],
            'second' => ['w', 'v'],
            'third' => ['y', 'z'],
        ];

        $results = [];
        $callbackResults = [];

        $iterator = Permutation::getGenerator($input, function ($permutation) use (&$callbackResults) {
            static::assertIsArray($permutation);
            $callbackResults[] = $permutation;
        });

        foreach ($iterator as $value) {
            $results[] = $value;
        }

        static::assertEquals([
            ['first' => 'a', 'second' => 'w', 'third' => 'y'],
            ['first' => 'b', 'second' => 'w', 'third' => 'y'],
            ['first' => 'a', 'second' => 'v', 'third' => 'y'],
            ['first' => 'b', 'second' => 'v', 'third' => 'y'],
            ['first' => 'a', 'second' => 'w', 'third' => 'z'],
            ['first' => 'b', 'second' => 'w', 'third' => 'z'],
            ['first' => 'a', 'second' => 'v', 'third' => 'z'],
            ['first' => 'b', 'second' => 'v', 'third' => 'z'],
        ], $results);

        static::assertEquals([
            ['first' => 'a', 'second' => 'w', 'third' => 'y'],
            ['first' => 'b', 'second' => 'w', 'third' => 'y'],
            ['first' => 'a', 'second' => 'v', 'third' => 'y'],
            ['first' => 'b', 'second' => 'v', 'third' => 'y'],
            ['first' => 'a', 'second' => 'w', 'third' => 'z'],
            ['first' => 'b', 'second' => 'w', 'third' => 'z'],
            ['first' => 'a', 'second' => 'v', 'third' => 'z'],
            ['first' => 'b', 'second' => 'v', 'third' => 'z'],
        ], $callbackResults);
    }

    public function testPermutationGeneratorRecursive()
    {
        $input = [
            'first' => ['a', 'b'],
            'second' => ['w', 'v'],
            'third' => ['y', 'z'],
        ];

        $results = [];

        $iterator = Permutation::getRecursiveGenerator($input);

        foreach ($iterator as $value) {
            $results[] = $value;
        }

        $expectedPermutations = [
            ['first' => 'a'],
            ['first' => 'b'],
            ['second' => 'w'],
            ['second' => 'v'],
            ['third' => 'y'],
            ['third' => 'z'],
            ['first' => 'a', 'second' => 'w'],
            ['first' => 'a', 'second' => 'v'],
            ['first' => 'a', 'third' => 'y'],
            ['first' => 'a', 'third' => 'z'],
            ['first' => 'b', 'second' => 'w'],
            ['first' => 'b', 'second' => 'v'],
            ['first' => 'b', 'third' => 'y'],
            ['first' => 'b', 'third' => 'z'],
            ['second' => 'w', 'third' => 'y'],
            ['second' => 'v', 'third' => 'y'],
            ['second' => 'w', 'third' => 'z'],
            ['second' => 'v', 'third' => 'z'],
            ['first' => 'a', 'second' => 'w', 'third' => 'y'],
            ['first' => 'b', 'second' => 'w', 'third' => 'y'],
            ['first' => 'a', 'second' => 'v', 'third' => 'y'],
            ['first' => 'b', 'second' => 'v', 'third' => 'y'],
            ['first' => 'a', 'second' => 'w', 'third' => 'z'],
            ['first' => 'b', 'second' => 'w', 'third' => 'z'],
            ['first' => 'a', 'second' => 'v', 'third' => 'z'],
            ['first' => 'b', 'second' => 'v', 'third' => 'z'],
        ];

        static::assertCount(count($expectedPermutations), $results);
        foreach ($expectedPermutations as $permutation) {
            static::assertContains($permutation, $results);
        }
    }
}
