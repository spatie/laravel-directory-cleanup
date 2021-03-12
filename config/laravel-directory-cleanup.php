<?php

return [

    'directories' => [

        /*
         * Here you can specify which directories need to be cleanup. All files older than
         * the specified amount of minutes or has extension matching was configured will be deleted.
         * You can use "*" or remove config `extensions` if you want clean all files
         * If you want clean mutiple extensions, sperate them by `,` E.g: "png,jpg"
         */

        /*
        'path/to/a/directory' => [
            'deleteAllOlderThanMinutes' => 60 * 24,
            'extensions' => "*" // "*"|"png,jpg"
        ],
        */
    ],

    /*
     * If a file is older than the amount of minutes specified, a cleanup policy will decide if that file
     * should be deleted. By default every file that is older than the specified amount of minutes
     * will be deleted.
     *
     * You can customize this behaviour by writing your own clean up policy. A valid policy
     * is any class that implements `Spatie\DirectoryCleanup\Policies\CleanupPolicy`.
     */
    'cleanup_policy' => \Spatie\DirectoryCleanup\Policies\DeleteEverything::class,
];
