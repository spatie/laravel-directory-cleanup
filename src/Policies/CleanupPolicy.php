<?php

namespace Spatie\DirectoryCleanup\Policies;

use Symfony\Component\Finder\SplFileInfo;

interface CleanupPolicy
{
    public function shouldDelete(SplFileInfo $file) : bool;
}
