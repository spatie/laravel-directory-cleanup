<?php

namespace Spatie\DirectoryCleanup\Test;

use Symfony\Component\Finder\SplFileInfo;
use Spatie\DirectoryCleanup\Policies\Policy;

class CustomCleanupPolicy extends Policy
{
    public function allow(SplFileInfo $file) : bool
    {
        $filesToKeep = ['keepThisFile.txt'];

        return ! in_array($file->getFilename(), $filesToKeep);
    }
}
