<?php

namespace WPModular\Filesystem;

use WPModular\Foundation\Services\Service;

class FilesystemService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(new FilesystemManager);
    }
}