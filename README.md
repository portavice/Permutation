# Permutation
[![Latest Version on Packagist](https://img.shields.io/packagist/v/portavice/permutation.svg?style=flat-square)](https://packagist.org/packages/portavice/permutation)
![Test Status](https://img.shields.io/github/actions/workflow/status/portavice/Permutation/tests.yml?branch=main&label=Tests)
![Code Style Status](https://img.shields.io/github/actions/workflow/status/portavice/Permutation/code-style.yml?branch=main&label=Code%20Style)
<a href="https://packagist.org/packages/portavice/permutation"><img src="https://img.shields.io/packagist/php-v/portavice/permutation.svg?style=flat-square" alt="PHP from Packagist"></a>
[![Total Downloads](https://img.shields.io/packagist/dt/portavice/permutation.svg?style=flat-square)](https://packagist.org/packages/portavice/permutation)

This is a simple permutation library for PHP.

It can be used to generate all possible permutations of a given array.

It can also be used to generate all possible permutations of a given array recursively. 
## Installation
To install this package with [Composer](https://getcomposer.org/):
To install it, just add the following to your `composer.json` file:

```bash
composer require portavice/permutation
```


## Methods
| Method                                                                                                                 | Static | Recursive |
|------------------------------------------------------------------------------------------------------------------------|--------|-----------|
| `permutate()`                                                                                                          | No     | No        |
| `getPermutations(array $input, bool $withSort = false)`                                                                | Yes    | No        |
| `getPermutationsWithCallback(array $input, callable $callback, bool $unsetAfterCall = false, mixed ...$args)`          | Yes    | No        |
| `permutateRecursive()`                                                                                                 | No     | Yes       |
| `getPermutationsRecursive(array $input, bool $withSort = false)`                                                       | Yes    | Yes       |
| `getPermutationsRecursiveWithCallback(array $input, callable $callback, bool $unsetAfterCall = false, mixed ...$args)` | Yes    | Yes       |
| `getResult(bool $sorted = false)`                                                                                      | No     |           |
| `setOffset(int $offset)`                                                                                               | No     |           |
| `setLimit(int $limit)`                                                                                                 | No     |           |
| `setCallback(callable $callback, bool $unsetAfterCall = false, mixed ...$args)`                                        | No     |           |


## Usage

```php
<?php
require_once 'vendor/autoload.php';

use Portavice\Permutation\Permutation;

// You can also use the static method:
$permutations = Permutation::getPermutations(
    [
        'a' => ['a1', 'a2'],
        'b' => ['b1', 'b2'],
        'c' => ['c1', 'c2'], 
    ]
);
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
$permutations = Permutation::getPermutationsRecursive(
    [
        'a' => ['a1', 'a2'],
        'b' => ['b1', 'b2'],
        'c' => ['c1', 'c2'], 
    ]
);
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

## Development

### How to develop
- Run `composer install` to install the dependencies for PHP.
- Run `composer test` to run all PHPUnit tests.
- Run `composer cs` to check compliance with the code style and `composer csfix` to fix code style violations before every commit.

### Code Style
PHP code MUST follow [PSR-12 specification](https://www.php-fig.org/psr/psr-12/).

We use [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for the PHP code style check.