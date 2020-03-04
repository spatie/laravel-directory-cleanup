# Delete old files in Laravel apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-directory-cleanup.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-directory-cleanup)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/spatie/laravel-directory-cleanup/run-tests?label=tests)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-directory-cleanup.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-directory-cleanup)
[![StyleCI](https://styleci.io/repos/57290433/shield?branch=master)](https://styleci.io/repos/57290433)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-directory-cleanup.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-directory-cleanup)

This package will delete old files from directories. You can use a configuration file to specify the maximum age of a file in a certain directory.

## Support us

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us). 

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-directory-cleanup
```

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in `config/app.php` file:

```php
'providers' => [
    ...
    Spatie\DirectoryCleanup\DirectoryCleanupServiceProvider::class,

];
```
Next, you must publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\DirectoryCleanup\DirectoryCleanupServiceProvider"
```
This is the content of the published config file `laravel-directory-cleanup`

```
return [

    'directories' => [

        /*
         * Here you can specify which directories need to be cleanup. All files older than
         * the specified amount of minutes will be deleted.
         */

        /*
        'path/to/a/directory' => [
            'deleteAllOlderThanMinutes' => 60 * 24,
        ],
        */
    ],

    /*
     * If a file is older than the amount of minutes specified, a cleanup policy will decide if that file
     * should be deleted. By default every file that is older that the specified amount of minutes
     * will be deleted.
     * 
     * You can customize this behaviour by writing your own clean up policy.  A valid policy
     * is any class that implements `Spatie\DirectoryCleanup\Policies\CleanupPolicy`.
     */
    'cleanup_policy' => \Spatie\DirectoryCleanup\Policies\DeleteEverything::class,
];
```

## Usage

Specify the directories that need cleaning in the config file.

When running the console command `clean:directories` all files in the specified directories older then `deleteAllOlderThanMinutes` will be deleted. Empty subdirectories will also be deleted.

This command can be scheduled in Laravel's console kernel.

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
   $schedule->command('clean:directories')->daily();
}

```

## Writing a custom clean up policy

If you want to apply additional conditional logic before a file is deleted, you can replace the default `cleanup_policy` with a custom one.
Create a class which implements `Spatie\DirectoryCleanup\Policies\CleanupPolicy` and add your logic to the `shouldDelete` method.

```php
// app/CleanupPolicies/MyPolicy.php

namespace App\CleanupPolicies;

use Symfony\Component\Finder\SplFileInfo;
use Spatie\DirectoryCleanup\Policies\CleanupPolicy;

class MyPolicy implements CleanupPolicy
{
    public function shouldDelete(SplFileInfo $file) : bool
    {
        $filesToKeep = ['robots.txt'];

        return ! in_array($file->getFilename(), $filesToKeep);
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Jolita Grazyte](https://github.com/JolitaGrazyte)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
