<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/dependencies.php';

$workerController = $injector->make('App\Controllers\WorkerController');
$workerController->processTransactions();