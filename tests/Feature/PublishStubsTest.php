<?php

declare(strict_types=1);

namespace SBordier44\Larastubs\Tests\Feature;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SBordier44\Larastubs\Tests\TestCase;

class PublishStubsTest extends TestCase
{
    public function testCanPublishStubs(): void
    {
        $stubForTest = 'controller.stub';

        $stubsTargetPath = $this->app->basePath(path: 'stubs');

        File::deleteDirectory(directory: $stubsTargetPath);

        /** @phpstan-ignore-next-line */
        $this->artisan(command: 'publish:stubs')->assertExitCode(Command::SUCCESS);

        $stubPath = __DIR__ . '/../../stubs/' . $stubForTest;

        $publishStubPath = $stubsTargetPath . DIRECTORY_SEPARATOR . $stubForTest;

        $this->assertFileEquals(expected: $stubPath, actual: $publishStubPath);
    }
}
