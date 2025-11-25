<?php

namespace App\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\State\Entities\State;
use App\Domain\State\Repositories\StateRepositoryInterface;

class StateService
{
    public function __construct(
        private StateRepositoryInterface $stateRepository
    ) { }

    public function getAllStates(): array
    {
        return $this->stateRepository->all();
    }

    public function getPaginatedStates(?int $perPage = 15): LengthAwarePaginator
    {
        return $this->stateRepository->paginate($perPage);
    }

    public function findStateById(int $id): ?State
    {
        return $this->stateRepository->find($id);
    }

    public function createState(array $data): State
    {
        return $this->stateRepository->create($data);
    }

    public function updateState(int $id, array $data): ?State
    {
        return $this->stateRepository->update($id, $data);
    }

    public function deleteState(int $id): void
    {
        $this->stateRepository->delete($id);
    }
}
