<?php

namespace App\Types\File;

use Illuminate\Http\UploadedFile;

class CompressImage {

    private $_file;

    private $_width;

    private $_height;

    private $_extension;

    public function __construct(UploadedFile $file, int $width, int $height, string $extension = 'jpg')
    {
        $this->_file = $file;
        $this->_width = $width;
        $this->_height = $height;
        $this->_extension = $extension;
    }

    public function getExtension() : string
    {
        return $this->_extension;
    }

    public function getWidth() : int
    {
        return $this->_width;
    }

    public function getHeight() : int
    {
        return $this->_height;
    }

    public function getFile() : UploadedFile
    {
        return $this->_file;
    }
}
