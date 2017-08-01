<?php

namespace WPModular\Filesystem;

use WPModular\Foundation\Services\Service;

/**
 * @method \League\Flysystem\Filesystem\Filesystem storage(string $name)
 */
class FilesystemService extends Service
{
    public function bootstrap()
    {
        $this->addMixin(new FilesystemManager);
    }
}