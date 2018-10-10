<?php

namespace Spatie\DirectoryCleanup\Test;

use Spatie\DirectoryCleanup\Policies\Policy;
use Symfony\Component\Finder\SplFileInfo;

class CustomCleanupPolicy extends Policy
{
    public function configure(SplFileInfo $file) : bool
    {
        $filesToKeep = ['keepThisFile.txt'];

        return ! in_array($file->getFilename(), $filesToKeep);
    }

}
