<?php

namespace Spatie\DirectoryCleanup\Test;

use Symfony\Component\Finder\SplFileInfo;

class CustomCleanupPolicy
{
    public function shouldBeDeleted(SplFileInfo $file) : bool
    {
        $filesToKeep = ['keepThisFile.txt']

        return ! in_array($file->getFilename(), $filesToKeep);
    }

}
