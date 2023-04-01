<?php

namespace App\Services\Actions;

use App\Exceptions\ProductError;
use App\Http\Requests\ProductRequest;
use App\Models\Product as ProductModel;
use App\Services\ModelFilters\ProductFilters\FilterProduct;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * @template TItem
 */
class Product
{
    /**
     * @param ProductRequest $request
     * @return ProductModel|Builder<ProductModel>|Model
     */
    public function createProduct(ProductRequest $request): ProductModel|Builder|Model
    {
        $data = $request->all();
        return ProductModel::query()->firstOrCreate([
            'category_uuid' => $data['category_uuid'],
            'title' => $data['title'],
            'metadata' => $this->getMetaData($request),
            'description' => $data['description'],
            'price' => $data['price'],
        ]);
    }

    /**
     * @param ProductRequest $request
     * @param string $uuid
     * @return Builder<ProductModel>|ProductModel
     * @throws ProductError
     */
    public function updateProduct(ProductRequest $request, string $uuid): Builder|ProductModel
    {
        $product = ProductModel::whereUuid($uuid)->first();
        if (!$product) {
            throw new ProductError('Product not found', 404);
        }

        $data = $request->all();

        $data['metadata'] = $this->getMetaData($request, $product);
        try {
            /**
             * Here, the model is only updated with
             * the filled request field
             */
            $product->update(array_filter(
                $data,
                function ($x) {
                    return !is_null($x);
                }
            ));
        } catch (Exception $exception) {
            $this->logError($exception);
        }
        return $product;
    }

    /**
     * @param string $uuid
     * @return void
     * @throws ProductError
     */
    public function deleteProduct(string $uuid): void
    {
        $product = ProductModel::whereUuid($uuid)->first();
        if (!$product) {
            throw new ProductError('Product not found', 404);
        }
        try {
            $product->delete();
        } catch (Exception $exception) {
            $this->logError($exception);
        }
    }

    /**
     * @param ProductRequest $request
     * @return LengthAwarePaginator<Model>
     */
    public function fetchProducts(ProductRequest $request): LengthAwarePaginator
    {
        $data = array_filter($request->all(), 'strlen');
        return FilterProduct::apply($data)
            ->latest()
            ->paginate($request->limit ?? 10);
    }

    /**
     * @param string $uuid
     * @return ProductModel
     * @throws ProductError
     */
    public function fetchProduct(string $uuid): ProductModel
    {
        $product = ProductModel::whereUuid($uuid)->first();
        if (!$product) {
            throw new ProductError('Product not found', 404);
        }
        return $product;
    }

    /**
     * @param Exception $exception
     * @return void
     * @throws ProductError
     */
    protected function logError(Exception $exception): void
    {
        Log::error($exception);
        throw new ProductError('An unexpected error was encountered', 500);
    }

    protected function getMetaData(ProductRequest $request, ?ProductModel $product = null): array
    {
        return [
            'brand' => $request->brand ?? $product?->metadata['brand'] ?? '',
            'image' => $request->image ?? $product?->metadata['image'] ?? '',

        ];
    }
}
