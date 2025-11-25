<?php

namespace App\Domain\Cluster\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Cluster\Entities\Cluster;

interface ClusterRepositoryInterface
{
    public function all(): array;
    public function paginate(?int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Cluster;
    public function create(array $data): Cluster;
    public function update(int $id, array $data): ?Cluster;
    public function delete(int $id): void;
}
