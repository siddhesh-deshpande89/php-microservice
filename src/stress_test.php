<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

$client = new Client();

// TODO fix script
$requests = function ($total) {
    $uri = 'http://devlocal/transactions.php';
    for ($i = 0; $i < $total; $i ++) {
        yield new Request('POST', $uri);
    }
};

$pool = new Pool($client, $requests(10000), [
    'concurrency' => 500,
    'fulfilled' => function (Response $response, $index) {
        // this is delivered each successful response
    },
    'rejected' => function (RequestException $reason, $index) {
        // this is delivered each failed request
    }
]);

// Initiate the transfers and create a promise
$promise = $pool->promise();

// Force the pool of requests to complete.
$promise->wait();
// $response = $client->request('GET', 'http://devlocal/transactions.php');

?>