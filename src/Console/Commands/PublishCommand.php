<?php

declare(strict_types=1);

namespace SBordier44\Larastubs\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class PublishCommand extends Command
{
    use ConfirmableTrait;

    /**
     * @var string
     */
    protected $signature = 'publish:stubs {--force : Overwrite existing customized stubs in project}';

    /**
     * @var string
     */
    protected $description = 'Publish all available stubs in project root';

    public function handle(Filesystem $filesystem): int
    {
        if (! $this->confirmToProceed()) {
            $this->output->warning(
                message: 'Cannot publish stubs without confirmation'
            );

            return Command::FAILURE;
        }

        if (! is_dir($stubsPath = $this->laravel->basePath('stubs'))) {
            $filesystem->makeDirectory(path: $stubsPath);
        }

        $stubs = collect(File::files(directory: __DIR__ . '/../../../stubs'))
            ->unless(
                value: $this->option(key: 'force'),
                callback: fn (Collection $files): Collection => $this->unpublished(files: $files)
            );

        $published = $this->publish(files: $stubs);

        $this->output->success(message: "Published $published / {$stubs->count()} stubs");

        return Command::SUCCESS;
    }

    private function targetPath(SplFileInfo $fileInfo): string
    {
        return $this->laravel->basePath('stubs') . '/' . $fileInfo->getFilename();
    }

    /**
     * @param Collection<int,SplFileInfo> $files
     * @return Collection<int,SplFileInfo>
     */
    private function unpublished(Collection $files): Collection
    {
        return $files->reject(
            callback: fn (SplFileInfo $fileInfo) => file_exists(filename: $this->targetPath(fileInfo: $fileInfo))
        );
    }

    /**
     * @param Collection<int,SplFileInfo> $files
     * @return int
     */
    private function publish(Collection $files): int
    {
        return $files->reduce(
            callback: function (int $published, SplFileInfo $fileInfo) {
                file_put_contents(
                    filename: $this->targetPath(fileInfo: $fileInfo),
                    data: file_get_contents(filename: $fileInfo->getPathname())
                );

                return $published + 1;
            },
            initial: 0
        );
    }
}
