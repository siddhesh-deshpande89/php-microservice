<?php
namespace Tests\Unit;

use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class DatabaseUnitTest extends TestCase
{

    private $database;

    public function setUp(): void
    {
        $injector = new \Auryn\Injector();
        $this->database = $injector->make('App\Helpers\Database');
    }

    /**
     * Test connection to database with valid credentials
     *
     * @test
     */
    public function databaseConnectionValidCase()
    {
        $host = Config::get('DATABASE_HOST');
        $username = Config::get('DATABASE_USER');
        $password = Config::get('DATABASE_PASS');
        $database = Config::get('DATABASE_NAME');

        $actual = $this->database->connect($host, $username, $password, $database);

        $this->assertNull($actual);
    }

    /**
     * Test connection to database with invalid credentials
     *
     * @test
     */
    public function databaseConnectionInvalidCase()
    {
        $actual = $this->database->connect('', '', '', '');

        $this->assertFalse($actual);
    }
}