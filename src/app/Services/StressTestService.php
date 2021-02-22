<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use App\Factories\TransactionFactory;
use App\Helpers\Logger;
use App\Helpers\Config;

class StressTestService
{

    /**
     * Transaction factory
     *
     * @var $transactionFactory
     */
    private $transactionFactory;

    /**
     * Stress Test Service constructor
     *
     * @param TransactionFactory $transactionFactory
     */
    public function __construct(TransactionFactory $transactionFactory)
    {
        $this->transactionFactory = $transactionFactory;
    }

    public function getMockData()
    {
        return $this->transactionFactory->createValidTransactions(100);
    }

    public function pickRandom(array $mockData)
    {
        $k = array_rand($mockData);
        return $mockData[$k];
    }

    /**
     * Runs the stress test
     *
     * @param int $total
     */
    public function run(int $total, int $concurrency)
    {
        $mockData = $this->getMockData();

        $client = new Client();

        $requests = function ($total) use ($mockData) {
            $uri = Config::get('APP_URL') . '/transactions';
            for ($i = 0; $i < $total; $i ++) {
                $postData = $this->pickRandom($mockData);
                print_r($postData);

                // $postData['http_errors'] = false;
                yield new Request('POST', $uri, [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ], http_build_query($postData, null, '&'));
            }
        };

        $pool = new Pool($client, $requests($total), [
            'concurrency' => $concurrency,
            'fulfilled' => function (Response $response, $index) {
                // this is delivered each successful response
                Logger::info('stresser', 'Request success');
            },
            'rejected' => function ($exception, $index) {
                Logger::error('stresser', 'Request rejected', [
                    'message' => $exception->getMessage()
                ]);
                var_dump($exception->getMessage());
            }
        ]);

        // Initiate the transfers and create a promise
        $promise = $pool->promise();

        // Force the pool of requests to complete.
        $promise->wait();
    }
}