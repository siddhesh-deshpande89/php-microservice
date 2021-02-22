<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TransactionUnitTest extends TestCase
{

    private $transactionFactory;

    private $transactionService;

    public function setUp(): void
    {
        $injector = new \Auryn\Injector();
        $this->transactionFactory = $injector->make('App\Factories\TransactionFactory');
        $this->transactionService = $injector->make('App\Services\TransactionService');
    }

    /**
     * Both sku and variant already exists before
     *
     * @test
     */
    public function duplicateBySkuAndVariantValidCase()
    {
        $variantId = Uuid::uuid4();
        $transaction = [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => $variantId
        ];

        $sku = 5;
        $variantId = $variantId;

        $actual = $this->transactionService->isDuplicateVariantSku($transaction, $sku, $variantId);

        $this->assertTrue($actual);
    }

    /**
     * Both sku and variant are unique
     *
     * @test
     */
    public function duplicateBySkuAndVariantInvalidCase()
    {
        $transaction = [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ];

        $sku = 6;
        $variantId = Uuid::uuid4();

        $actual = $this->transactionService->isDuplicateVariantSku($transaction, $sku, $variantId);

        $this->assertFalse($actual);
    }

    /**
     * Sku is unique but variant id exists before
     *
     * @test
     */
    public function duplicateByVariantIdOnlyValidCase()
    {
        $variantId = Uuid::uuid4();
        $transaction = [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => $variantId
        ];

        $sku = 6;
        $variantId = $variantId;

        $actual = $this->transactionService->isDuplicateVariantSku($transaction, $sku, $variantId);

        $this->assertTrue($actual);
    }

    /**
     * Sku exists before but variant id is unique
     *
     * @test
     */
    public function duplicateByVariantIdOnlyInvalidCase()
    {
        $transaction = [
            'id' => Uuid::uuid4(),
            'sku' => 5,
            'title' => 'Et quasi quis',
            'variant_id' => Uuid::uuid4()
        ];

        $sku = 5;
        $variantId = Uuid::uuid4();

        $actual = $this->transactionService->isDuplicateVariantSku($transaction, $sku, $variantId);

        $this->assertFalse($actual);
    }

    /**
     * Fail transaction where Product Sku does not exist
     *
     * @test
     */
    public function preventInsertTransactionSkuDoesNotExist()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);

        $transaction = $transaction[0];
        $transaction['sku'] = rand(9999, 99999);

        $actual = $this->transactionService->insertTransaction($transaction);

        $this->assertFalse($actual);
    }

    /**
     * Fail transaction where Variant Id does not exist
     *
     * @test
     */
    public function preventInsertTransactionVariantIdDoesNotExist()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);

        $transaction = $transaction[0];
        $transaction['variant_id'] = Uuid::uuid4();

        $actual = $this->transactionService->insertTransaction($transaction);

        $this->assertFalse($actual);
    }

    /**
     * Product sku exists in database valid case
     *
     * @test
     */
    public function checkProductExistsValidCase()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);
        $transaction = $transaction[0];

        $actual = $this->transactionService->productExists($transaction['sku']);

        $this->assertTrue($actual);
    }

    /**
     * Product sku exists in database invalid case
     *
     * @test
     */
    public function checkProductExistsInvalidCase()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);
        $transaction = $transaction[0];
        $transaction['sku'] = rand(9999, 399999);
        $actual = $this->transactionService->productExists($transaction['sku']);

        $this->assertFalse($actual);
    }

    /**
     * Variant exists in database valid case
     *
     * @test
     */
    public function checkVariantExistsValidCase()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);
        $transaction = $transaction[0];

        $actual = $this->transactionService->variantExists($transaction['variant_id']);

        $this->assertTrue($actual);
    }

    /**
     * Variant exists in database invalid case
     *
     * @test
     */
    public function checkVariantExistsInvalidCase()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);
        $transaction = $transaction[0];
        $transaction['variant_id'] = Uuid::uuid4();

        $actual = $this->transactionService->variantExists($transaction['variant_id']);

        $this->assertFalse($actual);
    }

    /**
     * Success transaction where Variant Id and Sku exist
     *
     * @test
     */
    public function checkInsertTransactionProductAndVariantIdExists()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);
        $transaction = $transaction[0];

        $actual = $this->transactionService->insertTransaction($transaction);

        $this->assertTrue($actual);
    }
}