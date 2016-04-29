<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DirectoryCleanupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'clean:directories';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up directories.';

    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $this->comment('Cleaning directories...');

        $this->cleanUp();

        $this->comment('All done!');
    }

    protected function cleanUp()
    {
        $directories = collect(config('laravel-directory-cleanup.directories'));

        collect($directories)->each(function ($directory) {

            $this->deleteFilesIfOlderThanMinutes($directory);

        });
    }

    protected function deleteFilesIfOlderThanMinutes(array $directory)
    {
        $minutes = $directory['deleteAllOlderThanMinutes'];

        collect($this->filesystem->files($directory['name']))
            ->filter(function ($file) use ($minutes) {

                $timeWhenFileWasModified = Carbon::createFromTimestamp(filemtime($file));
                $timeInPast = Carbon::now()->subMinutes($minutes);

                if ($timeInPast > $timeWhenFileWasModified) {
                    return $file;
                };

            })
            ->each(function ($file) {

                $this->filesystem->delete($file);
            });

        $this->info("Deleted expired file(s) from {$directory['name']}.");
    }
}
