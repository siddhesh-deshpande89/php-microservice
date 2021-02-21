<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Helpers\RequestHelper;
use Ramsey\Uuid\Uuid;
use App\Helpers\Config;
use App\Helpers\ApiResponse;

class TransactionTest extends TestCase
{

    /**
     * Transactions Api
     *
     * @test
     */
    public function transactionsApi()
    {
        // TODO move url to config
        $response = RequestHelper::makeRequest('POST', Config::get('APP_URL') . '/transactions', [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => 'df4a474f-f2c0-40ff-8d6b-6d6d28341bd1'
        ]);

        $this->assertEquals(ApiResponse::HTTP_OK, $response->getStatusCode());
    }
}