<?php

namespace App\Services\Product;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductImageService
{
    /**
     * @param UploadedFile $image
     * @return string
     */
    public function storeImage(UploadedFile $image): string
    {
        $imageIntervention = Image::make($image);
        $imageIntervention->resize(800, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $imageName = time() . '_' . $image->getClientOriginalName();
        Storage::disk('public')->put('images/' . $imageName, $imageIntervention->stream());

        return $imageName;
    }

    /**
     * @param string $imageName
     * @return void
     */
    public function deleteImage(string $imageName): void
    {
        Storage::disk('public')->delete('images/' . $imageName);
    }
}
