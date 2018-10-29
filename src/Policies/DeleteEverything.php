<?php

namespace Spatie\DirectoryCleanup\Policies;

use Symfony\Component\Finder\SplFileInfo;

class DeleteEverything implements CleanupPolicy
{
    public function shouldDelete(SplFileInfo $file) : bool
    {
        return true;
    }
}
