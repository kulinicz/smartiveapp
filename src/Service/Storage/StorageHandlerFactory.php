<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage;

use Symfony\Component\DependencyInjection\ContainerInterface;

class StorageHandlerFactory
{
    const TYPE_LOCAL = 'local';
    const TYPE_S3 = 's3';

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $type): StorageHandlerInterface
    {
        switch ($type) {
            case self::TYPE_LOCAL:
                return $this->container->get(LocalStorageHandler::class);
            case self::TYPE_S3:
                return $this->container->get(S3StorageHandler::class);
            default:
                throw new \InvalidArgumentException('Invalid storage type or missing bucket name.');
        }
    }
}
