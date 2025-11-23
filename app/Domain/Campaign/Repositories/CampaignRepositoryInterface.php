<?php

namespace App\Domain\Campaign\Repositories;

use App\Domain\Campaign\Entities\Campaign;

interface CampaignRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?Campaign;
    public function create(array $data): Campaign;
    public function update(int $id, array $data): ?Campaign;
    public function delete(int $id): void;
}
