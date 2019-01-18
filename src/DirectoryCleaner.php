<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Spatie\DirectoryCleanup\Policies\CleanupPolicy;

class DirectoryCleaner
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $directory;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function setDirectory(string $directory)
    {
        $this->directory = $directory;

        return $this;
    }

    public function deleteFilesOlderThanMinutes(int $minutes) : Collection
    {
        $timeInPast = Carbon::now()->subMinutes($minutes);

        return collect($this->filesystem->allFiles($this->directory, true))
            ->filter(function ($file) use ($timeInPast) {
                return Carbon::createFromTimestamp(filemtime($file))
                    ->lt($timeInPast);
            })
            ->filter(function ($file) {
                return $this->policy()->shouldDelete($file);
            })
            ->each(function ($file) {
                $this->filesystem->delete($file);
            });
    }

    public function deleteEmptySubdirectories() : Collection
    {
        return collect($this->filesystem->directories($this->directory))
            ->filter(function ($directory) {
                return ! $this->filesystem->allFiles($directory, true);
            })
            ->each(function ($directory) {
                $this->filesystem->deleteDirectory($directory);
            });
    }

    protected function policy() : CleanupPolicy
    {
        return resolve(config(
            'laravel-directory-cleanup.cleanup_policy',
            \Spatie\DirectoryCleanup\Policies\DeleteEverything::class
        ));
    }
}
