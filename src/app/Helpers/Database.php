<?php
namespace App\Helpers;

use PDO;
use PDOStatement;

class Database
{

    private $connection;

    /**
     * Database constructor
     */
    public function __construct()
    {
        $host = Config::get('DATABASE_HOST');
        $username = Config::get('DATABASE_USER');
        $password = Config::get('DATABASE_PASS');
        $database = Config::get('DATABASE_NAME');

        $this->connect($host, $username, $password, $database);
    }

   
    /**
     * Connect to database
     * 
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @return boolean
     */
    public function connect(string $host, string $username, string $password, string $database)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        } catch (\Exception $ex) {
            Logger::error('database', 'Error connecting to database', [
                'message' => $ex->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Prepare Query
     *
     * @param string $query
     * @return PDOStatement
     */
    public function prepare(string $query)
    {
        return $this->connection->prepare($query);
    }
}