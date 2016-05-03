<?php

namespace Spatie\DirectoryCleanup\Test;

use Carbon\Carbon;

class DirectoryCleanupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_cleanup_the_directories_specified_in_the_config_file()
    {
        $numberOfDirectories = 5;

        $directories = [];

        foreach (range(1, $numberOfDirectories) as $ageInMinutes) {
            $directories[$this->getTempDirectory($ageInMinutes, true)] = ['deleteAllOlderThanMinutes' => $ageInMinutes];
        }

        $this->app['config']->set('laravel-directory-cleanup', compact('directories'));

        foreach ($directories as $directory => $config) {
            foreach (range(1, $numberOfDirectories) as $ageInMinutes) {
                $this->createFile("{$directory}/{$ageInMinutes}MinutesOld.txt", $ageInMinutes);
            }
        }

        $this->artisan('clean:directories');

        foreach ($directories as $directory => $config) {
            foreach (range(1, $numberOfDirectories) as $ageInMinutes) {
                if ($ageInMinutes < $config['deleteAllOlderThanMinutes']) {
                    $this->assertFileExists("{$directory}/{$ageInMinutes}MinutesOld.txt");
                } else {
                    $this->assertFileNotExists("{$directory}/{$ageInMinutes}MinutesOld.txt");
                }
            }
        }
    }

    protected function createFile(string $fileName, int $ageInMinutes)
    {
        touch($fileName, Carbon::now()->subMinutes($ageInMinutes)->subSeconds(5)->timestamp);
    }
}
