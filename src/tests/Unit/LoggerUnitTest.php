<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\Logger;

class LoggerUnitTest extends TestCase
{

    private $logger;

    public function setUp(): void
    {
        $injector = new \Auryn\Injector();
        $this->logger = $injector->make('App\Helpers\Logger');
    }

    /**
     * Test logger is able to create log file
     *
     * @test
     */
    public function loggerTest()
    {
        $success = Logger::info('loggertest', 'Logger unit test');

        $this->assertTrue($success);
    }
}