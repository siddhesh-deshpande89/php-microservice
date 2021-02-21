<?php
namespace App\Helpers\MessageBrokers;

class MessageBroker
{

    private $default;

    private $host = 'rabbitpoc';

    private $port = 5672;

    private $username = 'siddhesh';

    private $password = 'demo1234';

    public function __construct()
    {
        // Change AQMP to another library in future if required
        $this->default = 'aqmp';
    }

    /**
     * Instantiate message broker library as per params
     *
     * @param string $name
     * @return object
     */
    public function instantiate(string $name)
    {
        $helper = 'App\\Helpers\\MessageBrokers\\Factories\\' . ucfirst($name) . 'Helper';
        return new $helper($this->host, $this->port, $this->username, $this->password);
    }

    /**
     * Publish to message queue
     *
     * @param string $queueName
     * @param array $params
     * @return array
     */
    public function publish(string $queueName, array $options)
    {
        return $this->instantiate($this->default)->publish($queueName, $options);
    }
}