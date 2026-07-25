<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductServiceContract
{
    /**
     * @param  array{query?:string|null,category_id?:int|null,status?:ProductStatus|string|null,seller_id?:int|null}  $filters
     * @return LengthAwarePaginator<int, Product>
     */
    public function publicIndex(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{category_id:int,name:string,description?:string|null,price:int|float|string,stock:int}  $attributes
     */
    public function createForSeller(User $seller, array $attributes): Product;

    /**
     * @param  array{category_id?:int,name?:string,description?:string|null,price?:int|float|string,stock?:int}  $attributes
     */
    public function update(Product $product, array $attributes): Product;

    public function changeStatus(Product $product, ProductStatus $status): Product;

    public function hide(Product $product): Product;
}
