<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\State\Entities\State;
use App\Domain\State\Repositories\StateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\StateModel;

class EloquentStateRepository implements StateRepositoryInterface
{
    public function all(): array
    {
        return StateModel::all()
            ->map(fn ($model) => $this->mapToEntity($model))
            ->toArray();
    }

    public function find(int $id): ?State
    {
        $model = StateModel::find($id);

        if (!$model) return null;

        return $model ? $this->mapToEntity($model) : null;
    }

    public function create(array $data): State
    {
        $model = StateModel::create($data);

        return $this->mapToEntity($model);
    }

    public function update(int $id, array $data): State
    {
        $model = StateModel::findOrFail($id);

        $model->update($data);

        return $this->mapToEntity($model);
    }

    public function delete(int $id): void
    {
        $model = StateModel::findOrFail($id);
        $model->delete();
    }

    private function mapToEntity(StateModel $model): State
    {
        return new State(
            id: $model->id,
            name: $model->name,
            uf: $model->uf
        );
    }
}
