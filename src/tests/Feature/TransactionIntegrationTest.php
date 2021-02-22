<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Helpers\Request;
use Ramsey\Uuid\Uuid;
use App\Helpers\Config;
use App\Helpers\ApiResponse;

class TransactionIntegrationTest extends TestCase
{

    /**
     * Test transactions api with valid data
     *
     * @test
     */
    public function transactionsApiValidData()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ]);

        $this->assertEquals(ApiResponse::HTTP_OK, $response->getStatusCode());
    }

    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiMissingId()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ]);

        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiMissingSku()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => Uuid::uuid4(),
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ]);

        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiMissingTitle()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'variant_id' => Uuid::uuid4()
        ]);

        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiMissingVariantId()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
        ]);
        
        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiInvalidId()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => 'abc',
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ]);

        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiInvalidSku()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => 'abc',
            'sku' => 'def',
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ]);

        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
    
    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiEmptyParams()
    {
        $response = Request::makeRequest('POST', Config::get('APP_URL') . '/transactions', []);
        
        $this->assertEquals(ApiResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}