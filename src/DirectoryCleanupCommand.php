<?php

namespace Spatie\DirectoryCleanup;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Spatie\DirectoryCleanup\Policies\CleanupPolicy;
use Spatie\DirectoryCleanup\Policies\DeleteEverything;

class DirectoryCleanupCommand extends Command
{
    protected $signature = 'clean:directories';

    protected $description = 'Clean up directories.';

    public function handle()
    {
        $this->comment('Cleaning directories...');

        $directories = collect(config('laravel-directory-cleanup.directories'));

        $defaultPolicy = config('laravel-directory-cleanup.cleanup_policy', DeleteEverything::class);

        collect($directories)->each(function ($config, $directory) use ($defaultPolicy) {
            if (File::isDirectory($directory)) {
                $policy = Arr::get($config, 'cleanup_policy', $defaultPolicy);

                $this->deleteFilesIfOlderThanMinutes(
                    $directory,
                    $config['deleteAllOlderThanMinutes'],
                    resolve($policy)
                );
                $this->deleteEmptySubdirectories($directory);
            }
        });

        $this->comment('All done!');
    }

    protected function deleteFilesIfOlderThanMinutes(string $directory, int $minutes, CleanupPolicy $policy)
    {
        $deletedFiles = app(DirectoryCleaner::class)
            ->setDirectory($directory)
            ->setMinutes($minutes)
            ->setPolicy($policy)
            ->deleteFilesOlderThanMinutes();

        $this->info("Deleted {$deletedFiles} file(s) from {$directory}.");
    }

    protected function deleteEmptySubdirectories(string $directory)
    {
        $deletedSubdirectories = app(DirectoryCleaner::class)
            ->setDirectory($directory)
            ->deleteEmptySubdirectories();

        $this->info("Deleted {$deletedSubdirectories->count()} directory(ies) from {$directory}.");
    }
}
