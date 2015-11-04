<?php

/*
 * This file is part of the rybakit/msgpack.php package.
 *
 * (c) Eugene Leonovich <gen.work@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use MessagePack\Tests\Benchmark\Benchmark;
use MessagePack\Tests\Benchmark\Packing;
use MessagePack\Tests\Benchmark\Unpacking;
use MessagePack\Tests\DataProvider;

require __DIR__.'/../vendor/autoload.php';

if (function_exists('xdebug_break')) {
    echo "The benchmark must be run with Xdebug extension disabled.\n";
    exit(42);
}

function run(Benchmark $benchmark, $testName = null, $skipSlow = null, $tableWidth = 32)
{
    echo str_repeat('=', $tableWidth)."\n";
    printf("Type: %s\n", $benchmark->getTitle());
    printf("Size: %s\n", $benchmark->getSize());
    echo str_repeat('=', $tableWidth)."\n";

    printf("Test %s Time, sec\n", str_repeat(' ', $tableWidth - 15));
    echo str_repeat('-', $tableWidth)."\n";

    $totalTime = 0;
    foreach (DataProvider::provideData() as $set) {
        if ($testName && $set[0] !== $testName) {
            continue;
        }

        echo $set[0];

        if ($skipSlow && !empty($set[3])) {
            printf(" %s S\n", str_repeat('.', $tableWidth - strlen($set[0]) - 3));
            continue;
        }

        $totalTime += $time = $benchmark->measure($set[1], $set[2]);
        $printTime = sprintf('%.4f', $time);

        printf(" %s %s\n",
            str_repeat('.', $tableWidth - strlen($set[0]) - strlen($printTime) - 2),
            $printTime
        );
    }

    $summary = sprintf('Total: %.4f', $totalTime);

    echo str_repeat('-', $tableWidth)."\n";
    echo str_repeat(' ', $tableWidth - strlen($summary)).$summary."\n\n";
}

$size = getenv('MP_BENCH_SIZE') ?: 1000;
$bench = getenv('MP_BENCH_TYPE') ?: 'p';
$test = getenv('MP_BENCH_TEST');
$skipSlow = !in_array(getenv('MP_BENCH_SKIP_SLOW'), ['0', 'false', 'off', ''], true);

if (!in_array($bench, ['p', 'u'], true)) {
    echo "Invalid benchmark type, use 'p' or 'u'.\n";
    exit(43);
}

run('p' === $bench ? new Packing($size) : new Unpacking($size), $test, $skipSlow);