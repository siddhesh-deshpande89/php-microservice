<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/dependencies.php';

$transactionController = $injector->make('App\Controllers\TransactionController');
$transactionController->processTransactions();