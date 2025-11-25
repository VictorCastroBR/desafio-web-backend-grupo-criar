<?php

namespace App\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Campaign\Entities\Campaign;
use App\Domain\Campaign\Repositories\CampaignRepositoryInterface;

class CampaignService
{
    public function __construct(
        private CampaignRepositoryInterface $campaignRepository,
    ) { }

    public function getAllCampaigns(): array
    {
        return $this->campaignRepository->all();
    }

    public function getPaginatedCampaigns(?int $perPage = 15): LengthAwarePaginator
    {
        return $this->campaignRepository->paginate($perPage);
    }

    public function findCampaignById(int $id): ?Campaign
    {
        return $this->campaignRepository->find($id);
    }

    public function createCampaign(array $data): Campaign
    {
        if (isset($data['active']) && $data['active'] === true)
            $this->campaignRepository->deactivateOtherCampaigns($data['cluster_id']);

        return $this->campaignRepository->create($data);
    }

    public function updateCampaign(int $id, array $data): ?Campaign
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) return null;

        if (isset($data['active']) && $data['active'] === true) {
            $clusterId = $data['cluster_id'] ?? $campaign->cluster->id;
            $this->campaignRepository->deactivateOtherCampaigns($clusterId, $id);
        }

        return $this->campaignRepository->update($id, $data);
    }

    public function deleteCampaign(int $id): void
    {
        $this->campaignRepository->delete($id);
    }
}
