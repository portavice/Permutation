# Permutation

This is a simple permutation library for PHP.

It can be used to generate all possible permutations of a given array.

It can also be used to generate all possible permutations of a given array recursively. 
## Installation
To install it, just add the following to your `composer.json` file:
```json
{
  "require": {
    "portavice/permutation": "^1.0"
  }
}
```
or run 
```bash
composer require portavice/permutation
```

Then run `composer install` or `composer update`.

## Methods
| Method                       | Static | Recursive |
|------------------------------|--------|-----------|
| `permutate()`                | No     | No        |
| `getPermutations()`          | Yes    | No        |
| `permutateRecursive()`       | No     | Yes       |
| `getPermutationsRecursive()` | Yes    | Yes       |
| `getResult()`                | No     | No        |


## Usage

```php
<?php
require_once 'vendor/autoload.php';

use Portavice\Permutation\Permutation;

// You can also use the static method:
$permutations = Permutation::getPermutations([
    'a' => ['a1', 'a2'],
    'b' => ['b1', 'b2'],
    'c' => ['c1', 'c2'], 
]);
// Output:
// [
//     ['a' => 'a1', 'b' => 'b1', 'c' => 'c1'],
//     ['a' => 'a1', 'b' => 'b1', 'c' => 'c2'],
//     ['a' => 'a1', 'b' => 'b2', 'c' => 'c1'],
//     ['a' => 'a1', 'b' => 'b2', 'c' => 'c2'],
//     ['a' => 'a2', 'b' => 'b1', 'c' => 'c1'],
//     ['a' => 'a2', 'b' => 'b1', 'c' => 'c2'],
//     ['a' => 'a2', 'b' => 'b2', 'c' => 'c1'],
//     ['a' => 'a2', 'b' => 'b2', 'c' => 'c2'],
// ]

// You can also use the recursive method:
$permutations = Permutation::getPermutationsRecursive([
    'a' => ['a1', 'a2'],
    'b' => ['b1', 'b2'],
    'c' => ['c1', 'c2'], 
]);
// Output:
// [
//     ['a' => 'a1'],
//     ['a' => 'a2'],
//     ['b' => 'b1'],
//     ['b' => 'b2'],
//     ['c' => 'c1'],
//     ['c' => 'c2'],
//     ['a' => 'a1', 'b' => 'b1'],
//     ['a' => 'a1', 'b' => 'b2'],
//     ['a' => 'a1', 'c' => 'c1'],
//     ['a' => 'a1', 'c' => 'c2'],
//     ['a' => 'a2', 'b' => 'b1'],
//     ['a' => 'a2', 'b' => 'b2'],
//     ['a' => 'a2', 'c' => 'c1'],
//     ['a' => 'a2', 'c' => 'c2'],
//     ['b' => 'b1', 'c' => 'c1'],
//     ['b' => 'b1', 'c' => 'c2'],
//     ['b' => 'b2', 'c' => 'c1'],
//     ['b' => 'b2', 'c' => 'c2'],
//     ['a' => 'a1', 'b' => 'b1', 'c' => 'c1'],
//     ['a' => 'a1', 'b' => 'b1', 'c' => 'c2'],
//     ['a' => 'a1', 'b' => 'b2', 'c' => 'c1'],
//     ['a' => 'a1', 'b' => 'b2', 'c' => 'c2'],
//     ['a' => 'a2', 'b' => 'b1', 'c' => 'c1'],
//     ['a' => 'a2', 'b' => 'b1', 'c' => 'c2'],
//     ['a' => 'a2', 'b' => 'b2', 'c' => 'c1'],
//     ['a' => 'a2', 'b' => 'b2', 'c' => 'c2'],
// ]
```

## License
This library is licensed under the MIT license.

## Author
This library was written by [Shaun Lüdeke](https://github.com/shaunluedeke) for [Portavice GmbH](https://portavice.de/).
