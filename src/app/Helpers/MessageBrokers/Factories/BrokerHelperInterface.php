<?php
namespace App\Helpers\MessageBrokers\Factories;

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

    public function publish(string $queueName, array $params);

    public function close() : void;
}