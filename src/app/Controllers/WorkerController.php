<?php
namespace App\Controllers;

use App\Helpers\MessageBrokers\MessageBroker;
use Http\Request;
use Http\Response;
class WorkerController
{
    private $request;
    
    private $response;
    
    /**
     * ProductController Constructor
     */
    public function __construct(MessageBroker $messageBroker, Request $request, Response $response)
    {
        $this->messageBroker = $messageBroker;
        $this->request = $request;
        $this->response = $response;
    }
    
    public function processTransactions()
    {
        echo "hi";
    }
}