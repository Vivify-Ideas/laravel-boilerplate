<?php

namespace App\Services\File;

use Intervention\Image\ImageManagerStatic as Image;
use App\Types\Files\CompressFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Types\File\CompressImage;

class FilesService {
    /**
     * Compress image to passed width and height
     *
     * @param string $path
     * @param UploadedFile $file
     * @param integer $width
     * @param integer $height
     * @return string
     */
    public function compressAndSaveImage(string $path, CompressImage $imageInfo) : string
    {
        $image = Image::make($imageInfo->getFile());
        $image->orientate();
        $resizedImage = $image->resize(
            $imageInfo->getWidth(),
            $imageInfo->getHeight(),
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        );
        $resizedImage = $resizedImage->encode($imageInfo->getExtension());
        Storage::disk('public')->put($path, $resizedImage);

        return $path;
    }

    /**
     * Remove image from disk
     *
     * @param string $path
     * @return void
     */
    public function removeImage(string $path)
    {
        Storage::disk('public')->delete($path);
    }
}
