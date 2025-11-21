<?php

namespace App\Domain\State\Repositories;

use App\Domain\State\Entities\State;

interface StateRepositoryInterface
{
    public function all(): array;
    public function find(int $data): State;
    public function create(array $data): State;
    public function update(int $id, array $data): State;
    public function delete(int $id): void;
}
