<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\CityService;
use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Http\Resources\City\CityResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(
        private CityService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);

        if ($request->has('paginate') && $request->get('paginate') === 'true') {
            $cities = $this->service->getPaginatedCities($perPage);
            return CityResource::collection($cities)->response();
        }

        $cities = $this->service->getAllCities();
        return response()->json(CityResource::collection($cities));
    }

    public function show(int $id): JsonResponse
    {
        $city = $this->service->findCityById($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        return response()->json(new CityResource($city));
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        $city = $this->service->createCity($request->validated());
        return response()->json(new CityResource($city), 201);
    }

    public function update(UpdateCityRequest $request, int $id): JsonResponse
    {
        $city = $this->service->updateCity($id, $request->validated());

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        return response()->json(new CityResource($city));
    }

    public function destroy(int $id): JsonResponse
    {
        $city = $this->service->findCityById($id);

        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }

        $this->service->deleteCity($id);
        return response()->json(null, 204);
    }
}
