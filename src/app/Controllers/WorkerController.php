<?php
namespace App\Controllers;

use App\Services\MessageBrokers\MessageBrokerService;
use Http\Request;
use Http\Response;

class WorkerController
{

    private $request;

    private $response;

    private $messageBrokerService;

    /**
     * TransactionController Constructor
     */
    public function __construct(MessageBrokerService $messageBrokerService, Request $request, Response $response)
    {
        $this->messageBrokerService = $messageBrokerService;
        $this->request = $request;
        $this->response = $response;
    }

    public function processTransactions()
    {
        echo "hi";
    }
}