<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Spatie\DirectoryCleanup\Policies\CleanupPolicy;

class DirectoryCleaner
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $directory;

    /** @var Carbon */
    protected $timeInPast;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function setDirectory(string $directory)
    {
        $this->directory = $directory;

        return $this;
    }

    public function setMinutes($minutes)
    {
        $this->timeInPast = Carbon::now()->subMinutes($minutes);

        return $this;
    }

    public function deleteFilesOlderThanMinutes($amountOfFilesDeleted = 0, $directory = null): int
    {
        $workingDir = $directory ?: realpath($this->directory);
        $directories = collect($this->filesystem->directories($workingDir));
        if ($directory === null) {
            $directories = $directories->add($workingDir);
        }

        foreach ($directories as $subDirectory) {
            $amountOfFilesDeleted = $this->deleteFilesOlderThanMinutes($amountOfFilesDeleted, $subDirectory);
        }

        $files = collect($this->filesystem->files($workingDir, true))
            ->filter(function ($file) {
                return Carbon::createFromTimestamp(filemtime($file))
                    ->lt($this->timeInPast);
            })
            ->filter(function ($file) {
                return $this->policy()->shouldDelete($file);
            })
            ->each(function ($file) {
                $this->filesystem->delete($file);
            });

        return $amountOfFilesDeleted + $files->count();
    }

    public function deleteEmptySubdirectories(): Collection
    {
        return collect($this->filesystem->directories($this->directory))
            ->filter(function ($directory) {
                return ! $this->filesystem->allFiles($directory, true);
            })
            ->each(function ($directory) {
                $this->filesystem->deleteDirectory($directory);
            });
    }

    protected function policy(): CleanupPolicy
    {
        return resolve(config(
            'laravel-directory-cleanup.cleanup_policy',
            \Spatie\DirectoryCleanup\Policies\DeleteEverything::class
        ));
    }
}
