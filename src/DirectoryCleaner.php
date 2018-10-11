<?php

namespace Spatie\DirectoryCleanup;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Spatie\DirectoryCleanup\Policies\Policy;

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
     * @param array $config
     *
     * @return \Illuminate\Support\Collection
     */
    public function deleteFilesOlderThanMinutes(array $config) : Collection
    {
        $timeInPast = Carbon::now()->subMinutes($config['deleteAllOlderThanMinutes']);

        return collect($this->filesystem->allFiles($this->directory))
            ->filter(function ($file) use ($timeInPast) {
                return Carbon::createFromTimestamp(filemtime($file))
                    ->lt($timeInPast);
            })
            ->filter(function ($file)  {
                return $this->policy()->allow($file);
            })
            ->each(function ($file) {
                $this->filesystem->delete($file);
            });
    }

    protected function policy() : Policy
    {
        return resolve(config(
            'laravel-directory-cleanup.cleanup_policy',
            \Spatie\DirectoryCleanup\Policies\Basic::class
        ));
    }
}
