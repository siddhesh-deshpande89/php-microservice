<?php
namespace App\Factories;

use App\Repositories\ProductRepository;
use App\Repositories\VariantRepository;

class TransactionFactory
{

    public function __construct(ProductRepository $productRepository, VariantRepository $variantRepository)
    {
        $this->productRepository = $productRepository;
        $this->variantRepository = $variantRepository;
    }

    public function createValidTransaction(array $products, array $variants)
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

    public function createValidTransactions($count)
    {
        $products = $this->productRepository->getRandomProducts(50);
        $variants = $this->variantRepository->getRandomVariants(50);

        $data = [];
        for ($i = 1; $i <= $count; $i ++) {

            $data[] = $this->createValidTransaction($products, $variants);
        }

        return $data;
    }

    private function getRandomData(array $data)
    {
        $k = array_rand($data);
        return $data[$k];
    }
}