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
}