<?php

namespace App\Http\Controllers\Api\V1;

use App\Application\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);

        if ($request->has('paginate') && $request->get('paginate') === 'true') {
            $products = $this->service->getPaginatedProducts($perPage);
            return ProductResource::collection($products)->response();
        }

        $products = $this->service->getAllProducts();
        return response()->json(ProductResource::collection($products));
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->service->findProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(new ProductResource($product));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->createProduct($request->validated());
        return response()->json(new ProductResource($product), 201);
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->service->updateProduct($id, $request->validated());

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(new ProductResource($product));
    }

    public function destroy(int $id): JsonResponse
    {
        $product = $this->service->findProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->service->deleteProduct($id);
        return response()->json(null, 204);
    }
}
