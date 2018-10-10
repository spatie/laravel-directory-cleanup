<?php

namespace Spatie\DirectoryCleanup\Policies;

use Symfony\Component\Finder\SplFileInfo;

abstract class Policy
{
    abstract public function configure(SplFileInfo $file) : bool;
}
