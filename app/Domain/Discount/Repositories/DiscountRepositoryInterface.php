<?php

namespace App\Domain\Discount\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Discount\Entities\Discount;

interface DiscountRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?Discount;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function create(array $data): Discount;
    public function update(int $id, array $data): ?Discount;
    public function delete(int $id): void;
}
