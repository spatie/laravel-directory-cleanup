<?php

namespace Spatie\DirectoryCleanup\Test;

use File;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\DirectoryCleanup\DirectoryCleanupServiceProvider;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->getTempDirectory('', true);
    }

    protected function getPackageProviders($app)
    {
        return [DirectoryCleanupServiceProvider::class];
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory, 0775, true);

        file_put_contents($directory.'/.gitignore', '*'.PHP_EOL.'!.gitignore');
    }

    protected function getTempDirectory($subDirectory = '', $createIfNotExists = false)
    {
        $fullDirectoryName = __DIR__."/temp/{$subDirectory}";

        if ($createIfNotExists) {
            $this->initializeDirectory($fullDirectoryName);
        }

        return $fullDirectoryName;
    }
}
