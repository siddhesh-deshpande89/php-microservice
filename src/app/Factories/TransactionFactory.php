<?php
namespace App\Factories;

use App\Repositories\ProductRepository;
use App\Repositories\VariantRepository;

class TransactionFactory
{

    /**
     * Transactions factory constructor
     *
     * @param ProductRepository $productRepository
     * @param VariantRepository $variantRepository
     */
    public function __construct(ProductRepository $productRepository, VariantRepository $variantRepository)
    {
        $this->productRepository = $productRepository;
        $this->variantRepository = $variantRepository;
    }

    /**
     * Creates transactions which are valid for database
     *
     * @param array $products
     * @param array $variants
     * @return array
     */
    public function createValidTransaction(array $products, array $variants): array
    {
        $product = $this->getRandomData($products);
        $variant = $this->getRandomData($variants);

        return [
            'id' => $product['id'],
            'sku' => $product['sku'],
            'variant_id' => $variant['id'],
            'title' => $product['title']
        ];
    }

    /**
     * Indicates how many valid transactions to create
     *
     * @param int $count
     * @return array
     */
    public function createValidTransactions($count): array
    {
        $products = $this->productRepository->getRandomProducts(50);
        $variants = $this->variantRepository->getRandomVariants(50);

        $data = [];
        for ($i = 1; $i <= $count; $i ++) {

            $data[] = $this->createValidTransaction($products, $variants);
        }

        return $data;
    }

    /**
     * Get random data from an array
     *
     * @param array $data
     * @return array
     */
    private function getRandomData(array $data): array
    {
        $k = array_rand($data);
        return $data[$k];
    }
}