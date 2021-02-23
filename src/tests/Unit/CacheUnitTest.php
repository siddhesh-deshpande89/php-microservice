<?php
use PHPUnit\Framework\TestCase;
use App\Helpers\Cache;

class CacheUnitTest extends TestCase
{

    public function setUp(): void
    {}

    /**
     * Write Cache test
     *
     * @test
     */
    public function writeCacheFile()
    {
        $actual = Cache::update('test', [
            'test_key' => 123
        ]);

        $this->assertTrue($actual);
    }
}