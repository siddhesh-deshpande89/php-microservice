<?php
namespace App\Controllers;

use App\Helpers\Logger;
use App\Helpers\Request;
use App\Helpers\ApiResponse;
use App\Services\TransactionService;
use App\Helpers\Validator;

class TransactionController
{

    /**
     * Request helper
     *
     * @var $request
     */
    private $request;

    /**
     * Validator helper
     *
     * @var $validator
     */
    private $validator;

    /**
     * Transaction service
     *
     * @var $transactionService
     */
    private $transactionService;

    /**
     * Tranaction controller
     *
     * @param TransactionService $transactionService
     * @param Request $request
     * @param Validator $validator
     */
    public function __construct(TransactionService $transactionService, Request $request, Validator $validator)
    {
        $this->transactionService = $transactionService;

        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * Validatation for transaction queue request
     *
     * @return
     */
    private function validateQueueRequest(array $params): array
    {
        $rules = [
            'sku' => [
                'required',
                'integer'
            ],
            'id' => [
                'required',
                'uuid'
            ],
            'title' => [
                'required'
            ],
            'variant_id' => [
                'required',
                'uuid'
            ]
        ];

        return $this->validator->validate($params, $rules);
    }

    /**
     * Put transaction in queue
     */
    public function queueTransaction()
    {
        $params = $this->request->getParameters();

        $validation = $this->validateQueueRequest($params);

        if ($this->validator->hasErrors($validation)) {

            Logger::info('transaction-api', 'Invalid input data', [
                $params
            ]);
            return ApiResponse::json(ApiResponse::HTTP_BAD_REQUEST, 'Invalid input data', $validation);
        }

        $response = $this->transactionService->queueTransaction($params)->handleApiResponse();

        return ApiResponse::json($response['status'], $response['message'], $response['data']);
    }
}
