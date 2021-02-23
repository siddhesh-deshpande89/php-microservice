<?php
use PHPUnit\Framework\TestCase;
use App\Helpers\Config;

class ConfigUnitTest extends TestCase
{

    public function setUp(): void
    {}

    /**
     * Get config value
     *
     * @test
     */
    public function getConfig()
    {
       $actual = Config::get('APP_URL');
       
       $this->assertStringStartsWith('http', $actual);
    }
}