<?php
namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RequestHelper
{

    public static function makeRequest($method, $url, $params)
    {
        $client = new Client();

//         try {
            return $client->request($method, $url, $params);
//         } catch (GuzzleException $ex) {

//             // TODO log
//         }
    }
}