<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Helpers\Request;
use Ramsey\Uuid\Uuid;
use App\Helpers\Config;
use App\Helpers\ApiResponse;

class TransactionTest extends TestCase
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
            'variant_id' => 'df4a474f-f2c0-40ff-8d6b-6d6d28341bd1'
        ]);

        $this->assertEquals(ApiResponse::HTTP_OK, $response->getStatusCode());
    }
    
    /**
     * Test transactions api with invalid data
     *
     * @test
     */
    public function transactionsApiInvalidData()
    {
       // TODO validation test
    }
}