<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\StateService;
use App\Http\Controllers\Controller;
use App\Http\Requests\State\StoreStateRequest;
use App\Http\Requests\State\UpdateStateRequest;
use App\Http\Resources\State\StateResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __construct(
        private StateService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);

        if ($request->has('paginate') && $request->get('paginate') === 'true') {
            $states = $this->service->getPaginatedStates($perPage);
            return StateResource::collection($states)->response();
        }

        $states = $this->service->getAllStates();
        return response()->json(StateResource::collection($states));
    }

    public function show(int $id): JsonResponse
    {
        $state = $this->service->findStateById($id);

        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }

        return response()->json(new StateResource($state));
    }

    public function store(StoreStateRequest $request): JsonResponse
    {
        $state = $this->service->createState($request->validated());
        return response()->json(new StateResource($state), 201);
    }

    public function update(UpdateStateRequest $request, int $id): JsonResponse
    {
        $state = $this->service->updateState($id, $request->validated());

        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }

        return response()->json(new StateResource($state));
    }

    public function destroy(int $id): JsonResponse
    {
        $state = $this->service->findStateById($id);

        if (!$state) {
            return response()->json(['message' => 'State not found'], 404);
        }

        $this->service->deleteState($id);
        return response()->json(null, 204);
    }
}
