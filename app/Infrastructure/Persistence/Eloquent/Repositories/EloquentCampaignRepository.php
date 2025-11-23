<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Campaign\Entities\Campaign;
use App\Domain\Campaign\Repositories\CampaignRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\CampaignModel;
use App\Domain\Cluster\Entities\Cluster;

class EloquentCampaignRepository implements CampaignRepositoryInterface
{
    public function all(): array
    {
        return CampaignModel::all()
            ->map(fn ($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function find(int $id): ?Campaign
    {
        $model = CampaignModel::find($id);
        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(array $data): Campaign
    {
        $model = CampaignModel::create($data);
        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): ?Campaign
    {
        $model = CampaignModel::find($id);

        if (!$model) return null;

        $model->update($data);

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        $model = CampaignModel::find($id);
        if ($model)
            $model->delete();
    }

    private function mapToEntity(CampaignModel $model): Campaign
    {
        $model->load('cluster');

        return new Campaign(
            id: $model->id,
            name: $model->name,
            active: $model->active,
            cluster: new Cluster(
                id: $model->cluster->id,
                name: $model->cluster->name
            )
        );
    }
}
