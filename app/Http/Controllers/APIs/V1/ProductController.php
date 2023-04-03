<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\ProductError;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product as ProductModel;
use App\Services\Actions\Product;
use App\Services\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * @param ProductRequest $productRequest
     * @param Product<ProductModel> $action
     * @return JsonResponse
     */
    public function createProduct(ProductRequest $productRequest, Product $action): JsonResponse
    {
        try {
            $product = $action->createProduct($productRequest);
        } catch (Exception $exception) {
            Log::error($exception);
            return ApiResponse::failed(
                'An unexpected error was encountered.',
                httpStatusCode: 500
            );
        }
        return ApiResponse::success($product);
    }

    /**
     * @param ProductRequest $productRequest
     * @param Product<ProductModel> $action
     * @param string $uuid
     * @return JsonResponse
     */
    public function updateProduct(ProductRequest $productRequest, Product $action, string $uuid): JsonResponse
    {
        try {
            $product = $action->updateProduct($productRequest, $uuid);
        } catch (ProductError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
        return ApiResponse::success($product);
    }

    /**
     * @param Product<ProductModel> $action
     * @param string $uuid
     * @return JsonResponse
     */
    public function deleteProduct(Product $action, string $uuid): JsonResponse
    {
        try {
            $action->deleteProduct($uuid);
        } catch (ProductError  $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }

        return ApiResponse::success();
    }

    /**
     * @param ProductRequest $productRequest
     * @param Product<ProductModel> $action
     * @return JsonResponse
     */
    public function fetchProducts(ProductRequest $productRequest, Product $action): JsonResponse
    {
        $products = $action->fetchProducts($productRequest);
        return ApiResponse::success(['products' => $products]);
    }

    /**
     * @param Product<ProductModel> $action
     * @param string $uuid
     * @return JsonResponse
     */
    public function fetchProduct(Product $action, string $uuid): JsonResponse
    {
        try {
            $product = $action->fetchProduct($uuid);
        } catch (ProductError  $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }

        return ApiResponse::success($product);
    }
}
