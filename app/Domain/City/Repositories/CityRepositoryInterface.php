<?php

namespace App\Domain\City\Repositories;

use App\Domain\City\Entities\City;

interface CityRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?City;
    public function create(array $data): City;
    public function update(int $id, array $data): City;
    public function delete(int $id): void;
}
