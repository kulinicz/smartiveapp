<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage;

interface StorageHandlerInterface
{
    public function save(string $path, string $content): void;
}
