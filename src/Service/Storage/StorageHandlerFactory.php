<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage;

use Aws\S3\S3Client;

class StorageHandlerFactory
{
    const TYPE_LOCAL = 'local';
    const TYPE_S3 = 's3';

    private S3Client $s3Client;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    public function create(string $type, ?string $bucketName = null): StorageHandlerInterface
    {
        if ($type === self::TYPE_LOCAL) {
            return new LocalStorageHandler();
        } elseif ($type === self::TYPE_S3 && $bucketName) {
            return new S3StorageHandler($this->s3Client, $bucketName);
        } else {
            throw new \InvalidArgumentException('Invalid storage type or missing bucket name.');
        }
    }
}
