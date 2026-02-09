<?php

namespace BasicDashboard\Web\OwnProducts\Services;

use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use Illuminate\Filesystem\FilesystemManager;

class OwnProductImageAction
{
    private const ROOT = 'OwnProducts';

    public function __construct(
        private FilesystemManager $fileSystemManager
    ) {}


    public function store(OwnProduct $ownProduct, $image): array
    {
        if (!$image) {
            return [];
        }
        $cloudPath = self::ROOT . '/' . $ownProduct->id;
        return config('cache.file_system_disk') === 'local'
            ? $this->fileSystemManager->uploadFileToLocal($image, self::ROOT, "image")
            : $this->fileSystemManager->uploadFileToCloud("digitalocean", $image, $cloudPath, "public", "image");
    }

    public function  update(OwnProduct $ownProduct, $image,$cloudServiceName): array
    {
        if (!$image) {
            return [];
        }
        $oldImage  = $ownProduct->image;
        $cloudPath = self::ROOT . '/' . $ownProduct->id;
        return config('cache.file_system_disk') === 'local'
            ? $this->fileSystemManager->updateFileFromLocal(
                $oldImage,
                $image,
                'image',
                self::ROOT,
            )
            : $this->fileSystemManager->updateFileFromCloud(
                $cloudServiceName,
                $oldImage,
                $image,
                $cloudPath,
                'public',
                'image'
            );
    }

    public function delete(OwnProduct $ownProduct,$cloudServiceName): void
    {
        config('cache.file_system_disk') == 'local' ?
            $this->fileSystemManager->deleteFileFromLocal($ownProduct->image) : 
            $this->fileSystemManager->deleteFileFromCloud($cloudServiceName,$ownProduct->image); 
    }
}
