<?php
namespace App\Services\MessageBrokers\Factories;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AqmpHelper implements BrokerHelperInterface
{

    /**
     * Connection for queue
     *
     * @var $connection
     */
    private $connection;

    /**
     * Channel for queue
     *
     * @var $channel
     */
    private $channel;

    public function __construct(string $host, int $port, string $username, string $password)
    {
        $this->connect($host, $port, $username, $password);
    }

    /**
     * Connect to channel
     *
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @return void
     */
    public function connect(string $host, int $port, string $username, string $password): void
    {
        $this->connection = new AMQPStreamConnection($host, $port, $username, $password);
        $this->channel = $this->connection->channel();
    }

    /**
     * Connect to channel
     *
     * @param string queueName
     * @param array $params
     * @return void
     */
    public function publish(string $queueName, array $params)
    {
        $this->channel->queue_declare($queueName, false, false, false, false);

        $msg = new AMQPMessage(json_encode($params));
        $this->channel->basic_publish($msg, '', $queueName);
        $this->close();
    }

    /**
     * Close connection to channel
     *
     * @param string queueName
     * @param array $params
     * @return void
     */
    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}