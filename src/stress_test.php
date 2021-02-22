<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * This is a standalone script to stress test transactions
 *
 * @author Siddhesh
 */
class StressTest
{

    public function run()
    {
        $client = new Client();

        // TODO fix script
        $requests = function ($total) {
            $uri = 'http://devlocal/transactions';
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
    }
}

$stressTest = new StressTest();
$stressTest->run();