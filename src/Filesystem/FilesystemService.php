<?php

namespace WPModular\Filesystem;

use WPModular\Foundation\Services\Service;

/**
 * @method \League\Flysystem\Filesystem storage(string $name)
 */
class FilesystemService extends Service
{
    public function bootstrap()
    {
        parent::bootstrap();

        $this->addMixin(
            $this->app->create(
                FilesystemManager::class
            )
        );
    }
}