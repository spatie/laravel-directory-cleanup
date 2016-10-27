<?php

namespace Spatie\DirectoryCleanup;

use Illuminate\Console\Command;

class DirectoryCleanupCommand extends Command
{
    /** @var string */
    protected $signature = 'clean:directories';

    /** @var string */
    protected $description = 'Clean up directories.';

    public function handle()
    {
        $this->comment('Cleaning directories...');

        $directories = collect(config('laravel-directory-cleanup.directories'));

        collect($directories)->each(function ($config, $directory) {

            $this->deleteFilesIfOlderThanMinutes(
                $directory,
                $config['deleteAllOlderThanMinutes'],
                $config['deleteAllWithPrefix']
            );

        });

        $this->comment('All done!');
    }

    protected function deleteFilesIfOlderThanMinutes(string $directory, int $minutes, string $prefix = null)
    {
        $deletedFiles = app(DirectoryCleaner::class)
            ->setDirectory($directory)
            ->deleteFilesOlderThanMinutes($minutes, $prefix);

        $this->info("Deleted {$deletedFiles->count()} file(s) from {$directory}.");
    }
}
