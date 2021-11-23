<?php
require_once __DIR__ . '/../vendor/autoload.php';

//echo "test\n";

use Spatie\Async\Pool;

$timer = 0;

$thenFunction = function (array $data) use (&$timer) {
    $iteration = $data[0];
    $sleep = $data[1];
    $timer += $sleep;

    echo "{$iteration} - {$sleep}s\n";
};

$catchFunction = function (Throwable $t) {
    echo "\n\n===\nError: " . $t->getMessage() . "\n===\n\n";
//    throw $t;
};

$pool = Pool::create();

$pool->add(function () {
    $sleep = random_int(0, 3);
    sleep($sleep);
    return [1, $sleep];
})->then($thenFunction)
    ->catch($catchFunction);

$pool->add(function () {
    $sleep = random_int(0, 3);
    sleep($sleep);
    return [2, $sleep];
})->then($thenFunction)
    ->catch($catchFunction);

$pool->add(function () {
    $sleep = random_int(0, 3);
    sleep($sleep);
//    throw new \Exception("3 - {$sleep}s");
    return [3, $sleep];
})->then($thenFunction)
    ->catch($catchFunction);

$pool->add(function () {
    $sleep = random_int(0, 3);
    sleep($sleep);
//    throw new \Exception("4 - {$sleep}s");
    return [4, $sleep];
})->then($thenFunction)
    ->catch($catchFunction);

await($pool);
echo "Expected time spent: $timer";

//echo $pool->status();
