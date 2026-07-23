<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

interface CategoryServiceContract
{
    /**
     * @return Collection<int, Category>
     */
    public function listForSelection(): Collection;

    /**
     * @param  array{name:string}  $attributes
     */
    public function create(array $attributes): Category;

    /**
     * @param  array{name:string}  $attributes
     */
    public function update(Category $category, array $attributes): Category;

    public function delete(Category $category): bool;
}
