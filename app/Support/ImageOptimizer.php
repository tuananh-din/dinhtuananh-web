<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;

class ImageOptimizer
{
    public static function encode(UploadedFile $file): string
    {
        $image = Image::read($file);
        $image->scaleDown(width: 1600);
        $extension = strtolower($file->getClientOriginalExtension());
        $options = in_array($extension, ['jpg', 'jpeg', 'webp'], true) ? ['quality' => 82] : [];

        return (string) $image->encodeByExtension($extension, ...$options);
    }
}
