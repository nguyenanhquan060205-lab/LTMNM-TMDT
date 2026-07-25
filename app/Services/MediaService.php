<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\MediaServiceContract;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use League\Flysystem\UnableToWriteFile;

class MediaService implements MediaServiceContract
{
    public function storeAvatar(User $user, UploadedFile $file): string
    {
        return $this->storeManagedFile('avatar', $file, 'user-'.$user->id);
    }

    public function storeProductImage(Product $product, UploadedFile $file): string
    {
        return $this->storeManagedFile('product_image', $file, 'product-'.$product->id);
    }

    public function storeMessageImage(User $sender, UploadedFile $file): string
    {
        return $this->storeManagedFile('message_image', $file, 'sender-'.$sender->id);
    }

    public function delete(string $path, ?string $disk = null): bool
    {
        if (! $this->isManagedPath($path)) {
            return false;
        }

        return Storage::disk($disk ?? $this->defaultDisk())->delete($path);
    }

    private function storeManagedFile(string $type, UploadedFile $file, string $prefix): string
    {
        $config = $this->uploadConfig($type);
        $extension = $this->extensionFor($file, $config['extensions']);
        $filename = $prefix.'-'.Str::uuid()->toString().'.'.$extension;

        $path = Storage::disk($config['disk'])->putFileAs($config['directory'], $file, $filename, [
            'disk' => $config['disk'],
            'visibility' => $config['visibility'],
        ]);

        if ($path === false) {
            throw UnableToWriteFile::atLocation($filename);
        }

        return str_replace('\\', '/', ltrim($path, '/'));
    }

    /**
     * @return array{disk:string,directory:string,extensions:list<string>,visibility:string}
     */
    private function uploadConfig(string $type): array
    {
        /** @var array{disk:string,directory:string,extensions:list<string>,visibility:string} $config */
        $config = config("uploads.types.{$type}");

        return $config;
    }

    /**
     * @param  list<string>  $allowedExtensions
     */
    private function extensionFor(UploadedFile $file, array $allowedExtensions): string
    {
        $extension = Str::lower((string) $file->extension());

        if ($extension === '' || ! in_array($extension, $allowedExtensions, true)) {
            throw new InvalidArgumentException('The uploaded file does not have an allowed extension.');
        }

        return $extension;
    }

    private function defaultDisk(): string
    {
        /** @var string $disk */
        $disk = config('uploads.default_disk', config('filesystems.default'));

        return $disk;
    }

    private function isManagedPath(string $path): bool
    {
        if ($path === '' || str_contains($path, '..') || str_starts_with($path, '/') || preg_match('/^[A-Za-z]:[\\\\\\/]/', $path) === 1) {
            return false;
        }

        /** @var array<string, array{directory:string}> $types */
        $types = config('uploads.types', []);

        foreach ($types as $config) {
            $directory = trim($config['directory'], '/').'/';

            if (str_starts_with($path, $directory)) {
                return true;
            }
        }

        return false;
    }
}
