<?php

namespace Portavice\Permutation;

use Generator;

class Permutation
{
    private array $input;
    private array $result;
    private int $offset;
    private int $limit;

    private mixed $callback = null;
    private bool $unsetAfterCall = false;
    private ?array $callbackArgs;
    public function __construct(array $input, int $offset = 0, int $limit = 0)
    {
        $this->input = array_map(
            fn ($valueList) => (is_array($valueList) ? array_values($valueList) : $valueList),
            $input
        );

        $this->result = [];
        $this->offset = $offset;
        $this->limit = $limit;
    }

    final public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    final public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    final public function setCallback(callable $callback, bool $unsetAfterCall = false, mixed ...$args): self
    {
        $this->callback = $callback;
        $this->callbackArgs = $args;
        $this->unsetAfterCall = $unsetAfterCall;
        return $this;
    }

    final public function permutate(): self
    {
        if (empty($this->input)) {
            return $this;
        }

        $permutationCount = 1;
        $matrixInfo = [];
        $cumulativeCount = 1;
        foreach ($this->input as $aColumn) {
            if (!is_array($aColumn)) {
                $aColumn = [$aColumn];
            }
            $columnCount = count($aColumn);
            $permutationCount *= $columnCount;
            $matrixInfo[] = [
                'count' => $columnCount,
                'cumulativeCount' => $cumulativeCount,
            ];
            $cumulativeCount *= $columnCount;
        }

        $arrayKeys = array_keys($this->input);
        $matrix = array_values($this->input);
        $columnCount = count($matrix);

        for ($currentPermutation = 0; $currentPermutation < $permutationCount; $currentPermutation++) {
            if ($this->offset > 0 && $currentPermutation < $this->offset) {
                continue;
            }
            if ($this->limit > 0 && $currentPermutation >= ($this->limit + $this->offset)) {
                break;
            }
            for ($currentColumnIndex = 0; $currentColumnIndex < $columnCount; $currentColumnIndex++) {
                $index = (int)($currentPermutation / $matrixInfo[$currentColumnIndex]['cumulativeCount'])
                    % $matrixInfo[$currentColumnIndex]['count'];
                $this->result[$currentPermutation][$currentColumnIndex] = $matrix[$currentColumnIndex][$index];
            }
            $this->result[$currentPermutation] = array_combine($arrayKeys, $this->result[$currentPermutation]);
            $this->sendCallback($this->result[$currentPermutation]);
            if ($this->unsetAfterCall) {
                unset($this->result[$currentPermutation]);
            }
        }
        return $this;
    }

    final public function permutateRecursive(): self
    {
        if (empty($this->input)) {
            return $this;
        }
        $result = [];
        foreach ($this->input as $key => $value) {
            if ($this->limit > 0 && count($result) >= ($this->limit + $this->offset)) {
                break;
            }
            if (!is_array($value)) {
                $value = [$value];
            } else {
                $value = $this->getValues($value);
            }
            foreach ($value as $item) {
                $result[] = [$key => $item];
                $this->sendCallback([$key => $item]);
                foreach ($result as $permutation) {
                    $permutation[$key] = $item;
                    if (!in_array($permutation, $result, true)) {
                        $result[] = $permutation;
                        $this->sendCallback($permutation);
                    }
                }
            }
        }
        if ($this->unsetAfterCall) {
            unset($result);
            return $this;
        }
        if ($this->offset > 0) {
            $result = array_slice($result, $this->offset);
        }
        $this->result = $result;
        return $this;
    }

    /**
     * Returns a generator for all permutations.
     *
     * @return Generator
     */
    final public function generator(): Generator
    {
        if (empty($this->input)) {
            return;
        }

        $permutationCount = 1;
        $matrixInfo = [];
        $cumulativeCount = 1;
        foreach ($this->input as $aColumn) {
            if (!is_array($aColumn)) {
                $aColumn = [$aColumn];
            }
            $columnCount = count($aColumn);
            $permutationCount *= $columnCount;
            $matrixInfo[] = [
                'count' => $columnCount,
                'cumulativeCount' => $cumulativeCount,
            ];
            $cumulativeCount *= $columnCount;
        }

        $arrayKeys = array_keys($this->input);
        $matrix = array_values($this->input);

        $start = max($this->offset, 0);

        $end = $permutationCount;
        if ($this->limit > 0) {
            $end = min(($this->offset + $this->limit), $end);
        }

        for ($p = $start; $p < $end; $p++) {
            $permutation = [];

            foreach ($matrix as $c => $values) {
                $i = (int) ($p / $matrixInfo[$c]['cumulativeCount']) % $matrixInfo[$c]['count'];

                $permutation[$arrayKeys[$c]] = $values[$i];
            }

            $this->sendCallback($permutation);
            yield $permutation;
        }
    }

    /**
     * Generates all permutations recursively.
     *
     * Note: Order of permutations is different from Permutation::permutateRecursive().
     *
     * @return Generator
     */
    final public function recursiveGenerator(): Generator
    {
        // copy input because we use array_shift to remove entries later
        $stack = array_replace([], $this->input);

        yield from $this->recurseGenerator($stack);
    }

    private function recurseGenerator(array $stack, ?array $lastPermutation = null): Generator
    {
        if (count($stack) === 0) {
            return;
        }

        do {
            $column = array_key_first($stack);
            $values = array_shift($stack);

            foreach ($values as $value) {
                if ($lastPermutation !== null) {
                    $permutation = [...$lastPermutation, $column => $value];
                } else {
                    $permutation = [$column => $value];
                }

                $this->sendCallback($permutation);
                yield $permutation;

                yield from $this->recurseGenerator($stack, $permutation);
            }
        } while (!empty($stack));
    }

    private function sendCallback(array $permutation): void
    {
        if ($this->callback !== null) {
            $args = $this->callbackArgs ?? [];
            $args[] = $permutation;
            call_user_func($this->callback, ...$args);
        }
    }

    private function getValues(array $input): array
    {
        $result = [];
        foreach ($input as $value) {
            if (is_array($value)) {
                $resultSub = array_merge($result, $this->getValues($value));
            } else {
                $resultSub[] = $value;
            }
            $result = $resultSub;
        }
        return $result;
    }

    final public function getResult(bool $withSort = false): array
    {
        if ($withSort) {
            usort($this->result, static fn($a, $b) => count($a) <=> count($b));
        }
        return $this->result;
    }

    public static function getPermutations(array $input, bool $withSort = false): array
    {
        return (new self($input))->permutate()->getResult($withSort);
    }

    public static function getPermutationsRecursive(array $input, bool $withSort = false): array
    {
        return (new self($input))->permutateRecursive()->getResult($withSort);
    }

    public static function getPermutationsWithCallback(
        array $input,
        callable $callback,
        bool $unsetAfterCall = false,
        mixed ...$args
    ): array {
        return (new self($input))->setCallback($callback, $unsetAfterCall, ...$args)->permutate()->getResult();
    }

    public static function getPermutationsRecursiveWithCallback(
        array $input,
        callable $callback,
        bool $unsetAfterCall = false,
        mixed ...$args
    ): array {
        return (new self($input))->setCallback($callback, $unsetAfterCall, ...$args)->permutateRecursive()->getResult();
    }

    public static function getGenerator(
        array $input,
        ?callable $callback = null,
        mixed ...$args
    ): Generator {
        $permutation = (new self($input));

        if ($callback !== null) {
            $permutation->setCallback($callback, false, ...$args);
        }

        return $permutation->generator();
    }

    public static function getRecursiveGenerator(
        array $input,
        ?callable $callback = null,
        mixed ...$args
    ): Generator {
        $permutation = (new self($input));

        if ($callback !== null) {
            $permutation->setCallback($callback, false, ...$args);
        }

        return $permutation->recursiveGenerator();
    }
}
