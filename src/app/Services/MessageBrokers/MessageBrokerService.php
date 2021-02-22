<?php
namespace App\Services\MessageBrokers;

use App\Helpers\Config;
use App\Helpers\ApiResponse;

class MessageBrokerService
{

    private $default;

    private $host;

    private $port;

    private $username;

    private $password;

    private $status;

    private $data;

    private $message;

    public function __construct()
    {
        // Change AQMP to another library in future if required
        $this->default = 'aqmp';

        $this->host = Config::get('BROKER_HOST');
        $this->port = Config::get('BROKER_PORT');
        $this->username = Config::get('BROKER_USER');
        $this->password = Config::get('BROKER_PASS');

        $this->data = [];
        $this->status = ApiResponse::HTTP_OK;
    }

    /**
     * Instantiate message broker library as per params
     *
     * @param string $name
     * @return object
     */
    public function instantiate(string $name)
    {
        $helper = 'App\\Services\\MessageBrokers\\Factories\\' . ucfirst($name) . 'Helper';
        return new $helper($this->host, $this->port, $this->username, $this->password);
    }

    /**
     * Publish to message queue
     *
     * @param string $queueName
     * @param array $params
     * @return array
     */
    public function publish(string $queueName, array $params): MessageBrokerService
    {
        $this->instantiate($this->default)->publish($queueName, $params);

        $this->message = 'Message published successfully.';
        $this->data = $params;

        return $this;
    }
    
    public function consume(string $queueName,array $callback)
    {
        $this->instantiate($this->default)->consume($queueName, $callback);
    }

    /**
     * Handles api response for this service
     *
     * @return array
     */
    public function handleApiResponse(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}