<?php

namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    protected static $configured = false;

    public static function upload($fileRealPath, $folder = 'images')
    {
        if (!self::$configured) {
            Configuration::instance(env('CLOUDINARY_URL'));
            self::$configured = true;
        }

        $upload = new UploadApi();
        $response = $upload->upload($fileRealPath, [
            'folder' => $folder,
        ]);

        return $response['secure_url'];
    }

    public static function delete($publicId)
    {
        if (!self::$configured) {
            Configuration::instance(env('CLOUDINARY_URL'));
            self::$configured = true;
        }

        $upload = new UploadApi();
        return $upload->destroy($publicId);
    }
}
