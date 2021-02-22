<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\ApiResponse;

class MessageBrokerUnitTest extends TestCase
{

    private $messageBrokerService;

    public function setUp(): void
    {
        $injector = new \Auryn\Injector();
        $this->messageBrokerService = $injector->make('App\Services\MessageBrokers\MessageBrokerService');
    }

    /**
     * Test Message broker can publish message
     *
     * @test
     */
    public function publishMessage()
    {
        $response = $this->messageBrokerService->publish('test_queue', [
            'message' => 'test'
        ])->handleApiResponse();

        $this->assertEquals(ApiResponse::HTTP_OK, $response['status']);
    }
}