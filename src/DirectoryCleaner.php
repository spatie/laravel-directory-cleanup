<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class DirectoryCleaner
{
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @var string */
    protected $directory;

    /**
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $directory
     *
     * @return $this
     */
    public function setDirectory(string $directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @param int $minutes
     *
     * @return \Illuminate\Support\Collection
     */
    public function deleteFilesOlderThanMinutes(int $minutes) : Collection
    {
        $timeInPast = Carbon::now()->subMinutes($minutes);

        return collect($this->filesystem->allFiles($this->directory))
            ->reject(function ($file) use ($config) {
                return in_array(
                    $file->getFilename(),
                    array_get($config, 'ignoredFiles', [])
                );
            })
            ->filter(function ($file) use ($timeInPast) {
                return Carbon::createFromTimestamp(filemtime($file))
                    ->lt($timeInPast);
            })
            ->each(function ($file) {
                $this->filesystem->delete($file);
            });
    }
}
