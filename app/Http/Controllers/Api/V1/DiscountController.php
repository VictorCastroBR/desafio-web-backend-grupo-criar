<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\DiscountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\StoreDiscountRequest;
use App\Http\Requests\Discount\UpdateDiscountRequest;
use App\Http\Resources\Discount\DiscountResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct(
        private DiscountService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);

        if ($request->has('paginate') && $request->get('paginate') === 'true') {
            $discounts = $this->service->getPaginatedDiscounts($perPage);
            return DiscountResource::collection($discounts)->response();
        }

        $discounts = $this->service->getAllDiscounts();
        return response()->json(DiscountResource::collection($discounts));
    }

    public function show(int $id): JsonResponse
    {
        $discount = $this->service->findDiscountById($id);

        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        return response()->json(new DiscountResource($discount));
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        try {
            $discount = $this->service->createDiscount($request->validated());
            return response()->json(new DiscountResource($discount), 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(UpdateDiscountRequest $request, int $id): JsonResponse
    {
        try {
            $discount = $this->service->updateDiscount($id, $request->validated());

            if (!$discount) {
                return response()->json(['message' => 'Discount not found'], 404);
            }

            return response()->json(new DiscountResource($discount));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $discount = $this->service->findDiscountById($id);

        if (!$discount) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        $this->service->deleteDiscount($id);
        return response()->json(null, 204);
    }
}
