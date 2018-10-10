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

    /** @test */
    public function it_can_cleanup_the_directories_specified_in_the_config_file_recursivly()
    {
        $numberSubOfDirectories = 5;

        $directories = [];

        $this->getTempDirectory('top/'.implode('/', range(1, $numberSubOfDirectories)), true);
        $directories[$this->getTempDirectory('top')] = ['deleteAllOlderThanMinutes' => 3];

        $this->app['config']->set('laravel-directory-cleanup', compact('directories'));

        $path = $this->getTempDirectory('top').'/';
        foreach (range(1, $numberSubOfDirectories + 1) as $level) {
            foreach (range(1, $numberSubOfDirectories) as $ageInMinutes) {
                $this->createFile("{$path}/{$ageInMinutes}MinutesOld.txt", $ageInMinutes);
            }
            $path .= "{$level}/";
        }

        $this->artisan('clean:directories');

        foreach ($directories as $directory => $config) {
            $path = $directory.'/';

            foreach (range(1, $numberSubOfDirectories + 1) as $level) {
                foreach (range(1, $numberSubOfDirectories) as $ageInMinutes) {
                    if ($ageInMinutes < $config['deleteAllOlderThanMinutes']) {
                        $this->assertFileExists("{$path}/{$ageInMinutes}MinutesOld.txt");
                    } else {
                        $this->assertFileNotExists("{$path}/{$ageInMinutes}MinutesOld.txt");
                    }
                }
                $path .= "{$level}/";
            }
        }
    }

    /** @test */
    public function it_can_cleanup_the_directories_specified_in_the_config_file_but_keep_some_files()
    {
        $directories[$this->getTempDirectory(1, true)] = [
            'deleteAllOlderThanMinutes' => 5,
            'ignoredFiles' => [
                'keepThisFile.txt'
            ]
        ];

        $cleanup_policy = \Spatie\DirectoryCleanup\Test\CustomCleanupPolicy::class;

        $this->app['config']->set('laravel-directory-cleanup', compact('directories', 'cleanup_policy'));

        foreach ($directories as $directory => $config) {
            $this->createFile("{$directory}/keepThisFile.txt", 5);
            $this->createFile("{$directory}/removeThisFile.txt", 5);
        }

        $this->artisan('clean:directories');

        foreach ($directories as $directory => $config) {
            $this->assertFileExists("{$directory}/keepThisFile.txt");
            $this->assertFileNotExists("{$directory}/removeThisFile.txt");
        }
    }

    protected function createFile(string $fileName, int $ageInMinutes)
    {
        touch($fileName, Carbon::now()->subMinutes($ageInMinutes)->subSeconds(5)->timestamp);
    }

    protected function createDirectory(string $fileName, int $ageInMinutes)
    {
        mkdir($fileName);
    }
}
