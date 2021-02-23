<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class VariantUnitTest extends TestCase
{

    private $variantRepository;

    private $transactionFactory;

    public function setUp(): void
    {
        $injector = new \Auryn\Injector();
        $this->variantRepository = $injector->make('App\Repositories\VariantRepository');
        $this->transactionFactory = $injector->make('App\Factories\TransactionFactory');
    }

    /**
     * Check if variant exists is giving boolean result
     *
     * @test
     */
    public function checkVariantExistsQuery()
    {
        $transaction = $this->transactionFactory->createValidTransactions(1);
        $transaction = $transaction[0];
        
        $actual = $this->variantRepository->checkExistsById($transaction['variant_id']);

        $this->assertTrue($actual);
    }
}