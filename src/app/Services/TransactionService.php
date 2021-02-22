<?php
namespace App\Services;

use App\Helpers\Cache;
use App\Repositories\TransactionRepository;
use App\Services\MessageBrokers\MessageBrokerService;
use App\Helpers\Logger;
use App\Repositories\ProductRepository;
use App\Repositories\VariantRepository;

class TransactionService
{

    /**
     * Message broker service
     *
     * @var $messageBrokerService
     */
    private $messageBrokerService;

    /**
     * Transaction Repository
     *
     * @var $transactionRepository
     */
    private $transactionRepository;

    /**
     * Product Repository
     * 
     * @var $productRepository
     */
    private $productRepository;

    /**
     * Variant Repository
     * 
     * @var $variantRepository
     */
    private $variantRepository;

    /**
     * Status of response
     *
     * @var $status
     */
    private $status;

    /**
     * Message of response
     *
     * @var $message
     */
    private $message;

    /**
     * Data for response
     *
     * @var $data
     */
    private $data;

    /**
     * Queue name for transactions
     *
     * @var string
     */
    private $queueName = 'transactions_queue';

    /**
     * Transaction Service Constructor
     *
     * @param MessageBrokerService $messageBrokerService
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(MessageBrokerService $messageBrokerService, TransactionRepository $transactionRepository, ProductRepository $productRepository, VariantRepository $variantRepository)
    {
        $this->messageBrokerService = $messageBrokerService;
        $this->transactionRepository = $transactionRepository;
        $this->productRepository = $productRepository;
        $this->variantRepository = $variantRepository;
    }

    /**
     * Queue a transaction
     *
     * @param array $params
     * @return TransactionService
     */
    public function queueTransaction(array $params): TransactionService
    {
        $response = $this->messageBrokerService->publish($this->queueName, $params)->handleApiResponse();

        $this->status = $response['status'];
        $this->message = 'Transaction queued successfully.';
        $this->data = $response['data'];

        return $this;
    }

    /**
     * Processes all transactions in a queue
     */
    public function processTransactions()
    {
        $this->messageBrokerService->consume($this->queueName, [
            $this,
            'workerCallback'
        ]);
    }

    /**
     * Transaction Worker callback
     *
     * @param object $message
     */
    public function workerCallback($message): bool
    {
        try {
            $transaction = json_decode($message->body, true);

            Logger::info('worker', 'Transaction received', [
                'content' => $transaction
            ]);

            $this->insertTransaction($transaction);

            echo ' [x] Received ', $message->body, "\n";
            sleep(substr_count($message->body, '.'));
            echo " [x] Done\n";

            return true;
        } catch (\Exception $ex) {

            Logger::error('worker', 'Error in transaction', [
                'content' => $message->body
            ]);

            return false;
        }
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

            if ($this->productExists($params['sku']) && $this->variantExists($params['variant_id'])) {

                return $this->transactionRepository->create($params);
            }
        }

        // If duplicate log
        Logger::debug('transactions', 'Duplicate transaction', $params);
        return false;
    }

    /**
     * Checks if product exists
     * 
     * @param int $sku
     * @return bool
     */
    public function productExists(int $sku): bool
    {
        return $this->productRepository->checkExistsById($sku);
    }

    /**
     * Checks if variant exists
     * 
     * @param string $variantId
     * @return bool
     */
    public function variantExists(string $variantId): bool
    {
        return $this->variantRepository->checkExistsById($variantId);
    }

    /**
     * Check if transaction exists
     *
     * @param int $sku
     * @param string $variantId
     * @return bool
     */
    public function isDuplicateTransaction(int $sku, string $variantId): bool
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
    public function isDuplicateVariantSku(array $transaction, int $sku, string $variantId): bool
    {
        // If any variant_id already exists return true
        if ($this->checkByVariantId($transaction['variant_id'], $variantId)) {
            return true;
        }

        // If Sku AND Variant both already exist return true
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