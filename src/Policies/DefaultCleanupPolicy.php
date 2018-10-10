<?php

namespace Spatie\DirectoryCleanup\Policies;

use Symfony\Component\Finder\SplFileInfo;

class DefaultCleanupPolicy
{
    public function shouldBeDeleted(SplFileInfo $file) : bool
    {
        return true;
    }
}
