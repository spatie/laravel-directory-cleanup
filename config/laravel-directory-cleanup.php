<?php

return [

    /*
     * A policy will determine if a given file should be deleted. This is the perfect
     * place to apply custom rules (like only deleting files with a certain extension).
     * A valid policy is any class that extends `Spatie\DirectoryCleanup\Policies\Policy`
     */
    'cleanup_policy' => \Spatie\DirectoryCleanup\Policies\Basic::class,

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
];
