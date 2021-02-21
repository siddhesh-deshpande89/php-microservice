<?php
namespace App\Services\MessageBrokers\Factories;

interface BrokerHelperInterface {

    /**
     * Connect to channel
     *
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @return void
     */
    public function connect(string $host, int $port, string $username, string $password): void;
    
    /**
     * Connect to channel
     *
     * @param string queueName
     * @param array $params
     * @return void
     */
    public function publish(string $queueName, array $params);

    /**
     * Close connection to channel
     *
     * @param string queueName
     * @param array $params
     * @return void
     */
    public function close(): void;
}