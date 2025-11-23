<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\ProductModel;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function all(): array
    {
        return ProductModel::all()
            ->map(fn ($m) => $this->mapToEntity($m))
            ->toArray();
    }

    public function find(int $id): ?Product
    {
        $model = ProductModel::find($id);

        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(array $data): Product
    {
        $model = ProductModel::create($data);

        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): ?Product
    {
        $model = ProductModel::find($id);

        if (!$model) return null;

        $model->update($data);

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        $model = ProductModel::find($id);
        if ($model)
            $model->delete();
    }

    private function mapToEntity(ProductModel $model): Product
    {
        return new Product(
            id: $model->id,
            name: $model->name,
            price: $model->price
        );
    }
}
