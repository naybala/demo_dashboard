<?php

namespace BasicDashboard\Foundations\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;

class WebFileStoreAction
{
    public function __construct(
        private FilesystemManager $fileSystemManager
    ) {}


    public function store(Model $model, $file,$featureName,$columnKey): array
    {
        if (!$file) {
            return [];
        }
        $cloudPath = $featureName . '/' . $model->id;
        return config('cache.file_system_disk') === 'local'
            ? $this->fileSystemManager->uploadFileToLocal($file, $featureName, $columnKey)
            : $this->fileSystemManager->uploadFileToCloud("digitalocean", $file, $cloudPath, "public", $columnKey);
    }

    public function  update(Model $model, $file,$cloudServiceName,$columnKey,$featureName): array
    {
        if (!$file) {
            return [];
        }
        $oldFile  = $model->$columnKey;
        $cloudPath = $featureName . '/' . $model->id;
        return config('cache.file_system_disk') === 'local'
            ? $this->fileSystemManager->updateFileFromLocal(
                $oldFile,
                $file,
                $columnKey,
                $featureName,
            )
            : $this->fileSystemManager->updateFileFromCloud(
                $cloudServiceName,
                $oldFile,
                $file,
                $cloudPath,
                'public',
                $columnKey
            );
    }

    public function delete(Model $model,$cloudServiceName,$columnKey): void
    {
        config('cache.file_system_disk') == 'local' ?
            $this->fileSystemManager->deleteFileFromLocal($model->$columnKey) : 
            $this->fileSystemManager->deleteFileFromCloud($cloudServiceName,$model->$columnKey); 
    }
}
