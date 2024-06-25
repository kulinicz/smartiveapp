<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage;

class LocalStorageHandler implements StorageHandlerInterface
{
    public function save(string $path, string $content): void
    {
        file_put_contents($path, $content);
    }
}
