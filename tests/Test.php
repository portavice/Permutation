<?php

namespace Portavice\Permutation\Tests;

use PHPUnit\Framework\TestCase;
use Portavice\Permutation\Permutation;

class Test extends TestCase
{
    private array $callBackOutput = [];

    final public function testPermutate(): void
    {
        $this->assertEquals([
            ['a', 'b', 'c'],
            ['a', 'b', 'd']
        ], Permutation::getPermutations([
            'a', 'b', ['c', 'd']
        ]));
    }

    final public function testPermutateRecursive(): void
    {
        $this->assertEquals([
            ['a'],
            [1 => 'b'],
            ['a', 'b'],
            [2 => 'c'],
            ['a', 2 =>  'c'],
            [1 => 'b', 2 => 'c'],
            ['a', 'b', 'c'],
            [2 => 'd'],
            ['a', 2 => 'd'],
            [1 => 'b', 2 => 'd'],
            ['a', 'b', 'd']
        ], Permutation::getPermutationsRecursive([
            'a', 'b', ['c', 'd']
        ]));
    }

    final public function testPermutateWithCallback(): void
    {
        $this->callBackOutput = [];
        Permutation::getPermutationsWithCallback([
            'a', 'b', ['c', 'd']
        ], [$this, 'permutationOutPutReturn'], true);
        $this->assertEquals([
            ['a', 'b', 'c'],
            ['a', 'b', 'd']
        ], $this->callBackOutput);
    }

    final public function testPermutateRecursiveWithCallback(): void
    {
        $this->callBackOutput = [];
        Permutation::getPermutationsRecursiveWithCallback([
            'a', 'b', ['c', 'd']
        ], [$this, 'permutationOutPutReturn'], true);
        $this->assertEquals([
            ['a'],
            [1 => 'b'],
            ['a', 'b'],
            [2 => 'c'],
            ['a', 2 =>  'c'],
            [1 => 'b', 2 => 'c'],
            ['a', 'b', 'c'],
            [2 => 'd'],
            ['a', 2 => 'd'],
            [1 => 'b', 2 => 'd'],
            ['a', 'b', 'd']
        ], $this->callBackOutput);
    }

    public function testManualIndices(): void
    {
        $result = Permutation::getPermutations([
            'first' => [
                0 => 'a',
                2 => 'b',
                3 => 'c',
            ],
            'second' => [
                'x',
                1 => 'y',
                'test' => 'z',
            ],
        ]);

        static::assertEquals([
            ['first' => 'a', 'second' => 'x'],
            ['first' => 'b', 'second' => 'x'],
            ['first' => 'c', 'second' => 'x'],
            ['first' => 'a', 'second' => 'y'],
            ['first' => 'b', 'second' => 'y'],
            ['first' => 'c', 'second' => 'y'],
            ['first' => 'a', 'second' => 'z'],
            ['first' => 'b', 'second' => 'z'],
            ['first' => 'c', 'second' => 'z'],
        ], $result);
    }

    final public function permutationOutPutReturn(array $permutation): void
    {
        $this->callBackOutput[] = $permutation;
    }
}
