<?php

namespace Spatie\DirectoryCleanup\Test;

use Spatie\DirectoryCleanup\DirectoryCleanupServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [DirectoryCleanupServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-directory-cleanup',
            [
                'directories' => [
                    ['name'  => __DIR__.'/temp1', 'time' => '5'],
                    ['name'  => __DIR__.'/temp2', 'time' => '1']
                ]
            ]);
    }

}
