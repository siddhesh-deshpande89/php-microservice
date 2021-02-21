<?php
namespace App\Controllers;

use Http\Request;
use App\Services\MessageBrokers\MessageBrokerService;
use App\Helpers\ApiResponse;

class ProductController
{

    private $request;

    private $messageBrokerService;

    /**
     * ProductController Constructor
     */
    public function __construct(MessageBrokerService $messageBrokerService, Request $request)
    {
        $this->messageBrokerService = $messageBrokerService;
        $this->request = $request;
    }

    /**
     * Insert request
     */
    public function insert()
    {
        $params['sku'] = $this->request->getParameter('sku');

        // TODO validate request params
        $response = $this->messageBrokerService->publish('product_queue', $params)->handleApiResponse();

        ApiResponse::json($response['status'], $response['message'], $response['data']);
    }
}
