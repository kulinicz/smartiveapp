<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

class S3StorageHandler implements StorageHandlerInterface
{
    private S3Client $s3Client;
    private string $bucketName;

    public function __construct(S3Client $s3Client, string $bucketName)
    {
        $this->s3Client = $s3Client;
        $this->bucketName = $bucketName;
    }

    public function save(string $path, string $content): void
    {
        $tempFilePath = sys_get_temp_dir() . '/' . basename($path);
        file_put_contents($tempFilePath, $content);

        try {
            $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $path,
                'SourceFile' => $tempFilePath,
            ]);
            unlink($tempFilePath);
        } catch (AwsException $e) {
            throw new \Exception('Failed to upload to S3: ' . $e->getMessage());
        }
    }
}
