<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function getImage(String $filename): Response
    {
        if (!Storage::disk('public')->exists('images/' . $filename)) {
            abort(404);
        }

        $contentType = File::mimeType(storage_path('app/public/images/' . $filename));

        return response(Storage::disk('public')->get('images/' . $filename), 200)->header('Content-Type', $contentType);
    }
}
