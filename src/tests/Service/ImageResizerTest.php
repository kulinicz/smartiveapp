<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Tests\Service;

use SergiuszKuliniczRekrutacjaSmartiveapp\Service\ImageResizer;
use PHPUnit\Framework\TestCase;

class ImageResizerTest extends TestCase
{
    private $imageResizer;

    protected function setUp(): void
    {
        $this->imageResizer = new ImageResizer();
    }

    public function testResizeImage()
    {
        // Assume we have a sample image file for testing
        $sampleImagePath = __DIR__ . '/../assets/input/sample.jpeg';
        $resizedContent = $this->imageResizer->resizeImage($sampleImagePath);

        $this->assertNotEmpty($resizedContent, 'Resized content should not be empty');
        // Further assertions to check the resized image properties
    }
}
