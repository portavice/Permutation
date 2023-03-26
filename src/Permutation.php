<?php
namespace Portavice\Permutation;

class Permutation
{

    private array $result;

    public function __construct(private array $input)
    {
        $this->result = [];
    }

    public function permutate(): self
    {
        if (empty($this->input)) {
            return $this;
        }

        $permutationCount = 1;
        $matrixInfo = [];
        $cumulativeCount = 1;
        foreach ($this->input as $aColumn) {
            if(!is_array($aColumn)) {
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
            for ($currentColumnIndex = 0; $currentColumnIndex < $columnCount; $currentColumnIndex++) {
                $index = (int)($currentPermutation / $matrixInfo[$currentColumnIndex]['cumulativeCount'])
                    % $matrixInfo[$currentColumnIndex]['count'];
                $this->result[$currentPermutation][$currentColumnIndex] = $matrix[$currentColumnIndex][$index];
            }
            $this->result[$currentPermutation] = array_combine($arrayKeys, $this->result[$currentPermutation]);
        }
        return $this;
    }

    public function permutateRecursive(): self
    {
        if (empty($this->input)) {
            return $this;
        }
        $result = [];
        foreach ($this->input as $key => $value) {
            if (!is_array($value)) {
                $value = [$value];
            } else {
                $value = $this->getValues($value);
            }
            foreach ($value as $item) {
                $result[] = [$key => $item];
                foreach ($result as $permutation) {
                    $permutation[$key] = $item;
                    if(!in_array($permutation, $result, true)){
                        $result[] = $permutation;
                    }
                }
            }
        }
        $this->result = $result;
        return $this;
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

    public function getResult(bool $withSort = false): array
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
}
