<?php

namespace App\Application\Services;

use App\Domain\Cluster\Entities\Cluster;
use App\Domain\Cluster\Repositories\ClusterRepositoryInterface;

class ClusterService
{
    public function __construct(
        private ClusterRepositoryInterface $clusterRepository,
    ) { }

    public function getAllClusters(): array
    {
        return $this->clusterRepository->all();
    }

    public function getPaginatedClusters(?int $perPage = 15): array
    {
        return $this->clusterRepository->paginate($perPage);
    }

    public function findClusterById(int $id): ?Cluster
    {
        return $this->clusterRepository->find($id);
    }

    public function createCluster(array $data): Cluster
    {
       return $this->clusterRepository->create($data);
    }

    public function updateCluster(int $id, array $data): ?Cluster
    {
        return $this->clusterRepository->update($id, $data);
    }

    public function deleteCluster(int $id): void
    {
        $this->clusterRepository->delete($id);
    }
}
