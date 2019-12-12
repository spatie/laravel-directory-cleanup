<?php

namespace Spatie\DirectoryCleanup\Test;

use Spatie\DirectoryCleanup\Policies\CleanupPolicy;
use Symfony\Component\Finder\SplFileInfo;

class CustomCleanupCleanupPolicy implements CleanupPolicy
{
    public function shouldDelete(SplFileInfo $file) : bool
    {
        $filesToKeep = ['keepThisFile.txt'];

        return ! in_array($file->getFilename(), $filesToKeep);
    }
}
