<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Tests\Service;

use SergiuszKuliniczRekrutacjaSmartiveapp\Service\ImageSaver;
use SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage\StorageHandlerFactory;
use SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage\StorageHandlerInterface;
use PHPUnit\Framework\TestCase;

class ImageSaverTest extends TestCase
{
    private $storageHandlerFactoryMock;
    private $storageHandlerMock;
    private $imageSaver;

    protected function setUp(): void
    {
        $this->storageHandlerFactoryMock = $this->createMock(StorageHandlerFactory::class);
        $this->storageHandlerMock = $this->createMock(StorageHandlerInterface::class);

        $this->storageHandlerFactoryMock
            ->method('create')
            ->willReturn($this->storageHandlerMock);

        $this->imageSaver = new ImageSaver($this->storageHandlerFactoryMock);
    }

    public function testSaveImageToLocal()
    {
        $path = 'local/path/to/image.jpg';
        $content = 'image content';
        $storageType = 'local';
        $bucketName = null;

        $this->storageHandlerMock
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($path), $this->equalTo($content));

        $this->imageSaver->saveImage($path, $content, $storageType, $bucketName);
    }

    public function testSaveImageToS3()
    {
        $path = 's3/path/to/image.jpg';
        $content = 'image content';
        $storageType = 's3';
        $bucketName = 'test-bucket';

        $this->storageHandlerMock
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($path), $this->equalTo($content));

        $this->imageSaver->saveImage($path, $content, $storageType, $bucketName);
    }
}
