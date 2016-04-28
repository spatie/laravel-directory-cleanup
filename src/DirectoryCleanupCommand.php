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

        collect($directories)->each(function($directory){

            $time = $directory['time'];

            collect($this->filesystem->files($directory['name']))->each(function($file) use ($time){

                $this->removeFile($file, $time);

            });
        });

    }

    protected function removeFile($file, $time)
    {
        $timeCreated = Carbon::createFromTimestamp(filectime( $file ));
        $timeModified = Carbon::createFromTimestamp(filemtime( $file ));
        $timePast = Carbon::now()->subMinutes($time);

        if($timePast > $timeCreated || $timePast > $timeModified)
        {
            $this->filesystem->delete($file);
        }
    }

}
