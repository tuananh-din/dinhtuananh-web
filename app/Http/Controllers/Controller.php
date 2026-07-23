<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function deleteManagedUpload(?string $url): void
    {
        if (empty($url)) {
            return;
        }

        if (!\Illuminate\Support\Str::startsWith($url, '/storage/')) {
            return;
        }

        $path = \Illuminate\Support\Str::replaceFirst('/storage/', 'public/', $url);

        try {
            \Illuminate\Support\Facades\Storage::delete($path);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Delete old upload failed: ' . $e->getMessage());
        }
    }
}
