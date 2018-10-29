<?php

namespace Spatie\DirectoryCleanup\Test;

use Symfony\Component\Finder\SplFileInfo;
use Spatie\DirectoryCleanup\Policies\CleanupPolicy;

class CustomCleanupCleanupPolicy implements CleanupPolicy
{
    public function shouldDelete(SplFileInfo $file) : bool
    {
        $filesToKeep = ['keepThisFile.txt'];

        return ! in_array($file->getFilename(), $filesToKeep);
    }
}
