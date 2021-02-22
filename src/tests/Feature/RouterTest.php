<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Helpers\ApiResponse;
use App\Helpers\Config;
use App\Helpers\Request;

class RouterTest extends TestCase
{

    /**
     * 404 Status code test
     *
     * @test
     */
    public function routerTest404StatusCode()
    {
        $response = Request::makeRequest('GET', Config::get('APP_URL') . '/randomurl');

        $this->assertEquals(ApiResponse::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    
    /**
     * 405 Status code test
     *
     * @test
     */
    public function routerTest405StatusCode()
    {
        $response = Request::makeRequest('GET', Config::get('APP_URL') . '/transactions');
        
        $this->assertEquals(ApiResponse::HTTP_NOT_ALLOWED, $response->getStatusCode());
    }
}