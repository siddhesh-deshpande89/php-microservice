<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProductUnitTest extends TestCase
{

    private $productRepository;

    public function setUp(): void
    {
        $injector = new \Auryn\Injector();
        $this->productRepository = $injector->make('App\Repositories\ProductRepository');
    }

    /**
     * Check if product exists is giving boolean result
     * 
     * @test
     */
    public function checkProductExistsQuery()
    {
        $actual = $this->productRepository->checkExistsById(2);
        
        $this->assertTrue($actual);
    }
}