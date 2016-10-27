<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

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
     * @param int    $minutes
     * @param string $prefix
     *
     * @return \Illuminate\Support\Collection
     */
    public function deleteFilesOlderThanMinutes(int $minutes, string $prefix = null) : Collection
    {
        $timeInPast = Carbon::now()->subMinutes($minutes);

        return collect($this->files($this->directory, $prefix))
            ->filter(function ($file) use ($timeInPast) {
                return Carbon::createFromTimestamp(filemtime($file))
                    ->lt($timeInPast);
            })
            ->each(function ($file) {
                $this->filesystem->delete($file);
            });
    }

    /**
     * Find files with given prefix
     *
     * @param string      $directory
     * @param string|null $prefix
     *
     * @return array
     */
    private function files(string $directory, string $prefix = null)
    {
        $glob = glob(sprintf('%s/%s', $directory, $prefix . '*' ?? '*'));

        if ($glob === false) {
            return [];
        }

        // To get the appropriate files, we'll simply glob the directory and filter
        // out any "files" that are not truly files so we do not end up with any
        // directories in our list, but only true files within the directory.
        return array_filter($glob, function ($file) {
            return filetype($file) == 'file';
        });
    }
}
