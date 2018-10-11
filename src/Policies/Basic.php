<?php

namespace Spatie\DirectoryCleanup\Policies;

use Symfony\Component\Finder\SplFileInfo;

class Basic extends Policy
{
    public function allow(SplFileInfo $file) : bool
    {
        return true;
    }
}
