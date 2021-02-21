<?php
namespace App\Helpers;

use PDO;
use PDOStatement;

class Database
{

    private $connection;

    private $host;

    private $username;

    private $password;

    private $database;

    public function __construct()
    {
        $this->host = Config::get('DATABASE_HOST');
        $this->username = Config::get('DATABASE_USER');
        $this->password = Config::get('DATABASE_PASS');
        $this->database = Config::get('DATABASE_NAME');

        $this->connect();
    }

    /**
     * Connect to database
     */
    public function connect()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
        } catch (\Exception $ex) {}
    }

    /**
     * Execute query
     *
     * @param string $query
     * @param array $params
     */
    public function executeQuery(string $query, array $params = [])
    {
        $query = $this->connection->prepare($query);

        $query->execute($params);
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