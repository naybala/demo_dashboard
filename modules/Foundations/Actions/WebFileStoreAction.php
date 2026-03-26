<?php

namespace BasicDashboard\Foundations\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;

class WebFileStoreAction
{
    public function __construct(
        private FilesystemManager $fileSystemManager
    ) {}


   
}
