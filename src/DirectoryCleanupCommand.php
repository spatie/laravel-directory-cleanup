<?php

namespace Spatie\DirectoryCleanup;

use File;
use Illuminate\Console\Command;

class DirectoryCleanupCommand extends Command
{
    protected $signature = 'clean:directories';

    protected $description = 'Clean up directories.';

    public function handle()
    {
        $this->comment('Cleaning directories...');

        $directories = collect(config('laravel-directory-cleanup.directories'));

        collect($directories)->each(function ($config, $directory) {
            if (File::isDirectory($directory)) {
                $this->deleteFilesIfOlderThanMinutes($directory, $config['deleteAllOlderThanMinutes']);
                $this->deleteEmptySubdirectories($directory);
            }
        });

        $this->comment('All done!');
    }

    protected function deleteFilesIfOlderThanMinutes(string $directory, int $minutes)
    {
        $deletedFiles = app(DirectoryCleaner::class)
            ->setDirectory($directory)
            ->deleteFilesOlderThanMinutes($minutes);

        $this->info("Deleted {$deletedFiles->count()} file(s) from {$directory}.");
    }

    protected function deleteEmptySubdirectories(string $directory)
    {
        $deletedSubdirectories = app(DirectoryCleaner::class)
            ->setDirectory($directory)
            ->deleteEmptySubdirectories();

        $this->info("Deleted {$deletedSubdirectories->count()} directory(ies) from {$directory}.");
    }
}
