<?php
namespace App\Helpers;

use GuzzleHttp\Client;

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

        if (strtolower($method) == 'post') {
            $params = [
                'json' => $params
            ];
        }
        try {
            $params['http_errors'] = false;
            return $client->request($method, $url, $params);
        } catch (\Exception $ex) {

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
        $params = (! empty($_REQUEST)) ? $_REQUEST : json_decode(file_get_contents("php://input"), true);

        return array_map('trim', $params);
    }
}