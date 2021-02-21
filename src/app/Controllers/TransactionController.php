<?php
namespace App\Controllers;

use Http\Request;
use App\Helpers\ApiResponse;
use App\Services\TransactionService;

class TransactionController
{

    private $request;

    private $transactionService;

    /**
     * TransactionController Constructor
     */
    public function __construct(TransactionService $transactionService, Request $request)
    {
        $this->transactionService = $transactionService;
        $this->request = $request;
    }

    /**
     * Put transaction in queue
     */
    public function queueTransaction()
    {
        // TODO validate request params
        $params['sku'] = $this->request->getParameter('sku');

        $response = $this->transactionService->queueTransaction($params)->handleApiResponse();

        return ApiResponse::json($response['status'], $response['message'], $response['data']);
    }
}
