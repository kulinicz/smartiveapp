<?php

namespace SergiuszKuliniczRekrutacjaSmartiveapp\Tests\Command;

use SergiuszKuliniczRekrutacjaSmartiveapp\Command\ResizeImagesCommand;
use SergiuszKuliniczRekrutacjaSmartiveapp\Service\ImageResizer;
use SergiuszKuliniczRekrutacjaSmartiveapp\Service\ImageSaver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Command\Command;

class ResizeImagesCommandTest extends TestCase
{
    private $imageResizer;
    private $imageSaver;
    private $commandTester;

    protected function setUp(): void
    {
        $this->imageResizer = $this->createMock(ImageResizer::class);
        $this->imageSaver = $this->createMock(ImageSaver::class);

        $command = new ResizeImagesCommand($this->imageResizer, $this->imageSaver);

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find('app:resize-images'));
    }

    public function testExecuteWithInvalidDirectory()
    {
        $this->commandTester->execute([
            'directory' => 'invalid_directory',
            'output' => 'output_directory',
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('The specified directory does not exist.', $output);
        $this->assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
    }

    public function testExecuteWithValidDirectory()
    {
        $directory = sys_get_temp_dir() . '/source';
        $outputDir = sys_get_temp_dir() . '/output';
        mkdir($directory, 0777, true);
        touch($directory . '/test.jpg');
        mkdir($outputDir, 0777, true);

        $this->imageResizer
            ->method('resizeImage')
            ->willReturn('resized_content');

        $this->imageSaver
            ->expects($this->once())
            ->method('saveImage')
            ->with($this->equalTo($outputDir . '/test.jpg'), $this->equalTo('resized_content'), $this->equalTo('local'));

        $this->commandTester->execute([
            'directory' => $directory,
            'output' => $outputDir,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Created thumbnail for ' . $directory . '/test.jpg', $output);
        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());

        // Clean up
        unlink($directory . '/test.jpg');
        rmdir($directory);
        rmdir($outputDir);
    }
}
