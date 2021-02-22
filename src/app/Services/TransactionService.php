<?php
namespace App\Services;

use App\Helpers\Cache;
use App\Repositories\TransactionRepository;
use App\Services\MessageBrokers\MessageBrokerService;
use App\Helpers\Logger;

class TransactionService
{

    private $messageBrokerService;

    private $transactionRepository;

    private $status;

    private $message;

    private $data;

    /**
     * Transaction Service Constructor
     *
     * @param MessageBrokerService $messageBrokerService
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(MessageBrokerService $messageBrokerService, TransactionRepository $transactionRepository)
    {
        $this->messageBrokerService = $messageBrokerService;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Queue a transaction
     *
     * @param array $params
     * @return TransactionService
     */
    public function queueTransaction(array $params): TransactionService
    {
        $response = $this->messageBrokerService->publish('product_queue', $params)->handleApiResponse();

        $this->status = $response['status'];
        $this->message = 'Transaction queued successfully.';
        $this->data = $response['data'];

        return $this;
    }

    /**
     * Insert transaction to database
     *
     * @param array $params
     * @return boolean
     */
    public function insertTransaction(array $params): bool
    {
        if (! $this->isDuplicateTransaction($params['sku'], $params['variant_id'])) {
            return $this->transactionRepository->create($params);
        }

        // If duplicate log
        Logger::debug('transactions', 'Duplicate transaction', $params);
        return false;
    }

    /**
     * Check if transaction exists
     *
     * @param int $sku
     * @param string $variantId
     * @return bool
     */
    protected function isDuplicateTransaction(int $sku, string $variantId): bool
    {
        $transactions = $this->getExistingTransactions();

        if (! empty($transactions)) {
            foreach ($transactions as $transaction) {

                if ($this->isDuplicateVariantSku($transaction, $sku, $variantId)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get Existing Transactions
     *
     * @return array
     */
    protected function getExistingTransactions(): array
    {
        $transactions = Cache::get('transactions');

        if (! empty($transactions)) {
            return $transactions;
        }

        // Fetch from Database
        return $this->transactionRepository->getAll();
    }

    /**
     * Checks if transaction variant or sku is unique
     *
     * @param array $transaction
     * @param int $sku
     * @param string $variantId
     * @return bool
     */
    protected function isDuplicateVariantSku(array $transaction, int $sku, string $variantId): bool
    {
        if ($this->checkByVariantId($transaction['variant_id'], $variantId)) {
            return true;
        }

        if ($this->checkBySkuAndVariantId($transaction, $sku, $variantId)) {
            return true;
        }

        return false;
    }

    /**
     * Check if variant_id is duplicate
     *
     * @param string $variantId1
     * @param string $variantId2
     * @return bool
     */
    protected function checkByVariantId(string $variantId1, string $variantId2): bool
    {
        return ($variantId1 == $variantId2) ? true : false;
    }

    /**
     * Check if sku and variant_id is duplicate
     *
     * @param array $transaction
     * @param int $sku
     * @param string $variantId
     * @return bool
     */
    protected function checkBySkuAndVariantId(array $transaction, int $sku, string $variantId): bool
    {
        return ($transaction['sku'] == $sku && $transaction['variant_id'] == $variantId) ? true : false;
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