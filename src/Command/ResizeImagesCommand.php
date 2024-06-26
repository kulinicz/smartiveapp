<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Command;

use SergiuszKuliniczRekrutacjaSmartiveapp\Service\ImageResizer;
use SergiuszKuliniczRekrutacjaSmartiveapp\Service\ImageSaver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:resize-images')]
class ResizeImagesCommand extends Command
{
    private ImageResizer $imageResizer;
    private ImageSaver $imageSaver;

    public function __construct(ImageResizer $imageResizer, ImageSaver $imageSaver)
    {
        parent::__construct();
        $this->imageResizer = $imageResizer;
        $this->imageSaver = $imageSaver;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates thumbnails for images in the specified directory')
            ->addArgument('directory', InputArgument::REQUIRED, 'The directory containing images')
            ->addArgument('output', InputArgument::REQUIRED, 'The directory or S3 key prefix to save thumbnails')
            ->addOption('storage', null, InputOption::VALUE_REQUIRED, 'The storage type (local or s3)', 'local')
            ->addOption('bucket', null, InputOption::VALUE_OPTIONAL, 'The S3 bucket name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $directory = $input->getArgument('directory');
        $outputDir = $input->getArgument('output');
        $storageType = $input->getOption('storage');

        if (!is_dir($directory)) {
            $io->error('The specified directory does not exist.');
            return Command::FAILURE;
        }

        if ($storageType === 'local' && !$this->createOutputDirectory($outputDir, $io)) {
            return Command::FAILURE;
        }

        $imageFiles = glob($directory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        foreach ($imageFiles as $file) {
            $this->processImageFile($file, $outputDir, $storageType, $io);
        }

        $io->success('All thumbnails have been created.');
        return Command::SUCCESS;
    }

    private function createOutputDirectory(string $outputDir, SymfonyStyle $io): bool
    {
        if (!is_dir($outputDir)) {
            if (!mkdir($outputDir, 0777, true) && !is_dir($outputDir)) {
                $io->error('The output directory could not be created.');
                return false;
            }
        }
        return true;
    }

    private function processImageFile(string $file, string $outputDir, string $storageType, SymfonyStyle $io): void
    {
        try {
            $resizedContent = $this->imageResizer->resizeImage($file);
            $this->imageSaver->saveImage($outputDir . '/' . basename($file), $resizedContent, $storageType);
            $io->success('Created thumbnail for ' . $file);
        } catch (\Exception $e) {
            $io->error('Failed to create thumbnail for ' . $file . ': ' . $e->getMessage());
        }
    }
}
