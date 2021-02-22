<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/dependencies.php';

$transactionService = $injector->make('App\Services\TransactionService');
$transactionService->processTransactions();