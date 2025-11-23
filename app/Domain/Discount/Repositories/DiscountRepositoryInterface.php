<?php

namespace App\Domain\Discount\Repositories;

use App\Domain\Discount\Entities\Discount;

interface DiscountRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?Discount;
    public function create(array $data): Discount;
    public function update(int $id, array $data): ?Discount;
    public function delete(int $id): void;
}
