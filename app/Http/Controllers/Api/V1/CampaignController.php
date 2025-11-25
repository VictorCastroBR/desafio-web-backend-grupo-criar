<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\CampaignService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Http\Resources\Campaign\CampaignResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);

        if ($request->has('paginate') && $request->get('paginate') === 'true') {
            $campaigns = $this->service->getPaginatedCampaigns($perPage);
            return CampaignResource::collection($campaigns)->response();
        }

        $campaigns = $this->service->getAllCampaigns();
        return response()->json(CampaignResource::collection($campaigns));
    }

    public function show(int $id): JsonResponse
    {
        $campaign = $this->service->findCampaignById($id);

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        return response()->json(new CampaignResource($campaign));
    }

    public function store(StoreCampaignRequest $request): JsonResponse
    {
        try {
            $campaign = $this->service->createCampaign($request->validated());
            return response()->json(new CampaignResource($campaign), 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(UpdateCampaignRequest $request, int $id): JsonResponse
    {
        try {
            $campaign = $this->service->updateCampaign($id, $request->validated());

            if (!$campaign) {
                return response()->json(['message' => 'Campaign not found'], 404);
            }

            return response()->json(new CampaignResource($campaign));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $campaign = $this->service->findCampaignById($id);

        if (!$campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        $this->service->deleteCampaign($id);
        return response()->json(null, 204);
    }
}
