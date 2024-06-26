<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Service\Storage;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Psr\Log\LoggerInterface;

class S3StorageHandler implements StorageHandlerInterface
{
    private S3Client $s3Client;
    private string $bucketName;
    private LoggerInterface $logger;

    public function __construct(S3Client $s3Client, string $bucketName, LoggerInterface $logger)
    {
        $this->s3Client = $s3Client;
        $this->bucketName = $bucketName;
        $this->logger = $logger;
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
            $this->logger->error('Failed to upload to S3', [
                'error' => $e->getMessage(),
                'bucket' => $this->bucketName,
                'path' => $path,
            ]);
            throw new \Exception('Failed to upload to S3: ' . $e->getMessage());
        }
    }
}
