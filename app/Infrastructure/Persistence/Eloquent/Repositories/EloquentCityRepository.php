<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\City\Entities\City;
use App\Domain\City\Repositories\CityRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\CityModel;
use App\Domain\State\Entities\State;
use App\Domain\Cluster\Entities\Cluster;

class EloquentCityRepository implements CityRepositoryInterface
{
    public function all(): array
    {
        return CityModel::all()
            ->map(fn ($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function paginate(?int $perPage = 15): LengthAwarePaginator
    {
        return CityModel::paginate($perPage)
            ->through(fn ($model) => $this->mapToEntity($model));
    }

    public function find(int $id): ?City
    {
        $model = CityModel::find($id);

        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(array $data): City
    {
        $model = CityModel::create($data);

        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): ?City
    {
        $model = CityModel::find($id);

        if (!$model) return null;

        $model->update($data);

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        $model = CityModel::find($id);
        if ($model)
            $model->delete();
    }

    private function mapToEntity(CityModel $model): City
    {
        $model->load(['state', 'cluster']);

        return new City(
            id: $model->id,
            name: $model->name,
            state: new State(
                id: $model->state->id,
                name: $model->state->name,
                uf: $model->state->uf
            ),
            cluster: new Cluster(
                id: $model->cluster->id,
                name: $model->cluster->name
            )
        );
    }
}
