<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Discount\Entities\Discount;
use App\Domain\Discount\Repositories\DiscountRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\DiscountModel;
use App\Domain\Campaign\Entities\Campaign;
use App\Domain\Cluster\Entities\Cluster;

class EloquentDiscountRepository implements DiscountRepositoryInterface
{
    public function all(): array
    {
        return DiscountModel::all()
            ->map(fn ($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function paginate(int $perPage = 15): array
    {
        return DiscountModel::paginate($perPage)
            ->map(fn ($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function find(int $id): ?Discount
    {
        $model = DiscountModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(array $data): Discount
    {
        $model = DiscountModel::create($data);

        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): ?Discount
    {
        $model = DiscountModel::find($id);

        if (!$model) return null;

        $model->update($data);

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        $model = DiscountModel::find($id);
        if ($model)
            $model->delete();
    }

    private function mapToEntity(DiscountModel $model): Discount
    {
        $model->load('campaign.cluster');

        return new Discount(
            id: $model->id,
            value: $model->value,
            percent: $model->percent,
            campaign: new Campaign(
                id: $model->campaign->id,
                name: $model->campaign->name,
                active: $model->campaign->active,
                cluster: new Cluster(
                    id: $model->campaign->cluster->id,
                    name: $model->campaign->cluster->name
                )
            )
        );
    }
}
