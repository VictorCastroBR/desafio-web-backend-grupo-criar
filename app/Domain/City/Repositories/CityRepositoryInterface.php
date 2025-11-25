<?php

namespace App\Domain\City\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\City\Entities\City;

interface CityRepositoryInterface
{
    public function all(): array;
    public function paginate(?int  $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?City;
    public function create(array $data): City;
    public function update(int $id, array $data): ?City;
    public function delete(int $id): void;
}
