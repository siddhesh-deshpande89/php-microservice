<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Request
{

    /**
     * Make HTTP request
     *
     * @param string $method
     * @param string $url
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public static function makeRequest(string $method, string $url, array $params = [])
    {
        $client = new Client();

        try {
            $params['http_errors'] = false;
            return $client->request($method, $url, $params);
        } catch (RequestException $ex) {

            Logger::error('requests', 'Error in request helper', [
                'message' => $ex->getMessage(),
                'params' => $params
            ]);
        }
    }

    /**
     * Sanitizes input and returns it
     *
     * @return array
     */
    public function getParameters(): array
    {
        return array_map('trim', $_REQUEST);
    }
}