<?php

namespace App\Application\Services;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) { }

    public function getAllProducts(): array
    {
        return $this->productRepository->all();
    }

    public function getPaginatedProducts(?int $perPage = 15): array
    {
        return $this->productRepository->paginate($perPage);
    }

    public function findProductById(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(array $data): Product
    {
       return $this->productRepository->create($data);
    }

    public function updateProduct(int $id, array $data): ?Product
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct(int $id): void
    {
        $this->productRepository->delete($id);
    }
}
