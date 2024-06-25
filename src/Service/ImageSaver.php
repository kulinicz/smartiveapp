<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service;

use SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage\StorageHandlerFactory;

class ImageSaver
{
    private StorageHandlerFactory $storageHandlerFactory;

    public function __construct(StorageHandlerFactory $storageHandlerFactory)
    {
        $this->storageHandlerFactory = $storageHandlerFactory;
    }

    public function saveImage(string $path, string $content, string $storageType, ?string $bucketName = null): void
    {
        $storageHandler = $this->storageHandlerFactory->create($storageType, $bucketName);
        $storageHandler->save($path, $content);
    }
}
