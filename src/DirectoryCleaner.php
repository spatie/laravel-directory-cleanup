<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class DirectoryCleaner
{
    /** @var \Illuminate\Support\Facades\Storage */
    protected $filesystem;

    /** @var string */
    protected $directory;

    /**
     * DirectoryCleaner constructor.
     */
    public function __construct()
    {
        $this->filesystem = Storage::disk();
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
            ->filter(function ($file) use ($timeInPast) {
                return Carbon::createFromTimestamp($this->filesystem->lastModified($file))
                    ->lt($timeInPast);
            })
            ->each(function ($file) {
                $this->filesystem->delete($file);
            });
    }
}
