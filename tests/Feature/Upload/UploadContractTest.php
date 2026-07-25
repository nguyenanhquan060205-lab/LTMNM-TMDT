<?php

namespace Tests\Feature\Upload;

use App\Contracts\Services\MediaServiceContract;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UploadContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_requests_accept_valid_images(): void
    {
        $category = Category::factory()->create();
        $receiver = User::factory()->create();

        $this->assertTrue(Validator::make([
            'full_name' => 'Valid User',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ], (new UpdateProfileRequest)->rules())->passes());

        $this->assertTrue(Validator::make([
            'category_id' => $category->id,
            'name' => 'Product',
            'price' => 1000,
            'stock' => 1,
            'images' => [UploadedFile::fake()->image('product.png')],
        ], (new StoreProductRequest)->rules())->passes());

        $this->assertTrue(Validator::make([
            'receiver_id' => $receiver->id,
            'image_path' => UploadedFile::fake()->image('message.jpg'),
        ], (new StoreMessageRequest)->rules())->passes());
    }

    public function test_upload_requests_reject_non_images_wrong_mime_and_oversized_files(): void
    {
        $avatarRules = (new UpdateProfileRequest)->rules();

        $this->assertFalse(Validator::make([
            'full_name' => 'Invalid User',
            'avatar' => UploadedFile::fake()->create('avatar.txt', 1, 'text/plain'),
        ], $avatarRules)->passes());

        $this->assertFalse(Validator::make([
            'full_name' => 'Invalid User',
            'avatar' => UploadedFile::fake()->create('avatar.jpg', 1, 'text/plain'),
        ], $avatarRules)->passes());

        $this->assertFalse(Validator::make([
            'full_name' => 'Invalid User',
            'avatar' => UploadedFile::fake()->image('avatar.jpg')->size(2049),
        ], $avatarRules)->passes());
    }

    public function test_media_service_stores_relative_paths_and_deletes_managed_files(): void
    {
        Storage::fake('public');

        /** @var MediaServiceContract $media */
        $media = app(MediaServiceContract::class);
        $user = User::factory()->create();
        $product = Product::factory()->create(['seller_id' => $user->id]);

        $avatarPath = $media->storeAvatar($user, UploadedFile::fake()->image('avatar.jpg'));
        $productPath = $media->storeProductImage($product, UploadedFile::fake()->image('product.png'));
        $messagePath = $media->storeMessageImage($user, UploadedFile::fake()->image('message.jpg'));

        Storage::disk('public')->assertExists($avatarPath);
        Storage::disk('public')->assertExists($productPath);
        Storage::disk('public')->assertExists($messagePath);
        $this->assertStringStartsWith('avatars/', $avatarPath);
        $this->assertStringStartsWith('products/', $productPath);
        $this->assertStringStartsWith('messages/', $messagePath);
        $this->assertFalse($media->delete('../outside.jpg'));
        $this->assertTrue($media->delete($avatarPath));
        Storage::disk('public')->assertMissing($avatarPath);
    }

    public function test_media_service_generates_normalized_unique_managed_paths(): void
    {
        Storage::fake('public');

        /** @var MediaServiceContract $media */
        $media = app(MediaServiceContract::class);
        $user = User::factory()->create();

        $firstPath = $media->storeAvatar($user, UploadedFile::fake()->image('avatar.jpg'));
        $secondPath = $media->storeAvatar($user, UploadedFile::fake()->image('avatar.jpg'));

        $this->assertNotSame($firstPath, $secondPath);
        $this->assertStringStartsWith('avatars/', $firstPath);
        $this->assertStringNotContainsString('\\', $firstPath);
        Storage::disk('public')->assertExists($firstPath);
        Storage::disk('public')->assertExists($secondPath);
    }

    public function test_media_service_refuses_managed_path_traversal(): void
    {
        /** @var MediaServiceContract $media */
        $media = app(MediaServiceContract::class);

        $this->assertFalse($media->delete('/avatars/avatar.jpg'));
        $this->assertFalse($media->delete('avatars/../../outside.jpg'));
    }
}
