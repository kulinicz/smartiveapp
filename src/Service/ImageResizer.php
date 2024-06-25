<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class ImageResizer
{
    private Imagine $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resizeImage(string $inputPath): string
    {
        $image = $this->imagine->open($inputPath);
        $size = $image->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        if ($width > $height) {
            $newWidth = 150;
            $newHeight = (150 / $width) * $height;
        } else {
            $newHeight = 150;
            $newWidth = (150 / $height) * $width;
        }

        $thumbnail = $image->thumbnail(new Box($newWidth, $newHeight), ImageInterface::THUMBNAIL_OUTBOUND);

        return $thumbnail->get('jpg', ['jpeg_quality' => 90]);
    }
}
