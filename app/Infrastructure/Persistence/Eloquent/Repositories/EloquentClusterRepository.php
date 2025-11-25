<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Cluster\Entities\Cluster;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\ClusterModel;

class EloquentClusterRepository implements ClusterRepositoryInterface
{
    public function all(): array
    {
        return ClusterModel::all()
            ->map(fn ($m) => $this->mapToEntity($m))
            ->toArray();
    }

    public function paginate(?int $perPage = 15): LengthAwarePaginator
    {
        return ClusterModel::paginate($perPage)
            ->through(fn ($model) => $this->mapToEntity($model));
    }

    public function find(int $id): ?Cluster
    {
        $model = ClusterModel::find($id);

        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(array $data): Cluster
    {
        $model = ClusterModel::create($data);

        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): ?Cluster
    {
        $model = ClusterModel::find($id);

        if (!$model) return null;

        $model->update($data);

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        $model = ClusterModel::find($id);
        if ($model)
            $model->delete();
    }

    private function mapToEntity(ClusterModel $model): Cluster
    {
        return new Cluster(
            id: $model->id,
            name: $model->name
        );
    }
}
