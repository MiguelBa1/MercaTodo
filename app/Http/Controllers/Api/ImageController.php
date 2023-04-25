<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function getImage($filename): Response
    {
        if (!Storage::disk('public')->exists($filename)) {
            abort(404);
        }

        $contentType = File::mimeType(storage_path('app/public/images' . $filename));

        return response(Storage::disk('public')->get($filename), 200)->header('Content-Type', $contentType);
    }}
