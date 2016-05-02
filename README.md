# Delete old files in Laravel apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-directory-cleanup.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-directory-cleanup)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/laravel-directory-cleanup/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-directory-cleanup)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/22e41e1f-1f6b-4c90-9727-d20851a41ad9.svg?style=flat-square)](https://insight.sensiolabs.com/projects/22e41e1f-1f6b-4c90-9727-d20851a41ad9)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-directory-cleanup.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-directory-cleanup)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-directory-cleanup.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-directory-cleanup)

This package will delete old files from directories. You can use a configuration file to specify the maximum age of a file in a certain directory.

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-directory-cleanup
```

Next up, the service provider must be registered:

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
        
        /**
         * Here you can specify which directories need to be cleanup. All files older than
         * the specified amount of minutes will be deleted.
         */

        /*
        'path/to/a/directory' => [
            'deleteAllOlderThanMinutes' => 60 * 24
        ],
        */
    ],
];
```

## Usage

Specify the directories that need cleaning in the config file.

When running the console command `clean:directories` all files in the specified directories older then `deleteAllOlderThanMinutes` will be deleted.

This command can be scheduled in Laravel's console kernel.

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
   $schedule->command('clean:directories')->daily();
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

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
