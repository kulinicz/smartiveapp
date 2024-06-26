<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class ImageResizer
{
    const MAX_WIDTH = 150;
    const JPG_QUALITY = 90;

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
            $newWidth = self::MAX_WIDTH;
            $newHeight = (self::MAX_WIDTH / $width) * $height;
        } else {
            $newHeight = self::MAX_WIDTH;
            $newWidth = (self::MAX_WIDTH / $height) * $width;
        }

        $thumbnail = $image->thumbnail(new Box($newWidth, $newHeight), ImageInterface::THUMBNAIL_OUTBOUND);

        return $thumbnail->get('jpg', ['jpeg_quality' => self::JPG_QUALITY]);
    }
}
