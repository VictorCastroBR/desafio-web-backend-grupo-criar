<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\ClusterService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cluster\StoreClusterRequest;
use App\Http\Requests\Cluster\UpdateClusterRequest;
use App\Http\Resources\Cluster\ClusterResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    public function __construct(
        private ClusterService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);

        if ($request->has('paginate') && $request->get('paginate') === 'true') {
            $clusters = $this->service->getPaginatedClusters($perPage);
            return ClusterResource::collection($clusters)->response();
        }

        $clusters = $this->service->getAllClusters();
        return response()->json(ClusterResource::collection($clusters));
    }

    public function show(int $id): JsonResponse
    {
        $cluster = $this->service->findClusterById($id);

        if (!$cluster) {
            return response()->json(['message' => 'Cluster not found'], 404);
        }

        return response()->json(new ClusterResource($cluster));
    }

    public function store(StoreClusterRequest $request): JsonResponse
    {
        $cluster = $this->service->createCluster($request->validated());
        return response()->json(new ClusterResource($cluster), 201);
    }

    public function update(UpdateClusterRequest $request, int $id): JsonResponse
    {
        $cluster = $this->service->updateCluster($id, $request->validated());

        if (!$cluster) {
            return response()->json(['message' => 'Cluster not found'], 404);
        }

        return response()->json(new ClusterResource($cluster));
    }

    public function destroy(int $id): JsonResponse
    {
        $cluster = $this->service->findClusterById($id);

        if (!$cluster) {
            return response()->json(['message' => 'Cluster not found'], 404);
        }

        $this->service->deleteCluster($id);
        return response()->json(null, 204);
    }
}
