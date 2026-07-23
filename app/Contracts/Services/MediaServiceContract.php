<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;

interface MediaServiceContract
{
    public function storeAvatar(User $user, UploadedFile $file): string;

    public function storeProductImage(Product $product, UploadedFile $file): string;

    public function storeMessageImage(User $sender, UploadedFile $file): string;

    public function delete(string $path, ?string $disk = null): bool;
}
