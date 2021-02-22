<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/dependencies.php';

$requests = 1000;
$concurrency = 500;
$stressTestService = $injector->make('App\Services\StressTestService');
$stressTestService->run($requests, $concurrency);