<?php

namespace Spatie\DirectoryCleanup\Test;

use Carbon\Carbon;

class DirectoryCleanupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $tempDirectory = $this->getTempDirectory();

        touch("{$tempDirectory}1/file1.txt", Carbon::now()->subMinutes(15)->timestamp);
        touch("{$tempDirectory}1/file2.txt", Carbon::now()->timestamp);
        touch("{$tempDirectory}2/file1.txt", Carbon::now()->subMinutes(10)->timestamp);
        touch("{$tempDirectory}2/file2.txt", Carbon::now()->timestamp);
    }

    /** @test */
    public function it_can_cleanup_the_directories_specified_in_the_config_file_with_artisan_calling_command()
    {
        $this->assertFilesInDirectoryBeforeCleanup();

        $this->artisan('clean:directories');

        $this->assertFilesLeftInDirectoryAfterCleanup();

        $this->assertFilesNotInDirectoryAfterCleanup();
    }

    protected function getTempDirectory()
    {
        return __DIR__.'/temp';
    }

    protected function assertFilesInDirectoryBeforeCleanup()
    {
        collect(['file1.txt', 'file2.txt'])->each(function ($file) {

            $this->assertFileExists($this->getTempDirectory().'1/'.$file);

            $this->assertFileExists($this->getTempDirectory().'2/'.$file);

        });
    }

    protected function assertFilesLeftInDirectoryAfterCleanup()
    {
        $this->assertFileExists($this->getTempDirectory().'1/file2.txt');

        $this->assertFileExists($this->getTempDirectory().'2/file2.txt');
    }

    protected function assertFilesNotInDirectoryAfterCleanup()
    {
        $this->assertFileNotExists($this->getTempDirectory().'1/file1.txt');

        $this->assertFileNotExists($this->getTempDirectory().'2/file1.txt');
    }
}
