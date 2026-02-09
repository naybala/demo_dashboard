<?php

namespace BasicDashboard\Web\Users\Services;

use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Filesystem\FilesystemManager;

class UserImageAction
{
    private const ROOT = 'Users';
    private const FIELD = 'avatar';

    public function __construct(
        private FilesystemManager $fileSystemManager
    ) {}

    public function store(User $user, $image): array
    {
        if (!$image) {
            return [];
        }
        $cloudPath = self::ROOT . '/' . $user->id;
        return config('cache.file_system_disk') === 'local'
            ? $this->fileSystemManager->uploadFileToLocal($image, self::ROOT, self::FIELD)
            : $this->fileSystemManager->uploadFileToCloud("digitalocean", $image, $cloudPath, "public", self::FIELD);
    }

    public function update(User $user, $image, $cloudServiceName): array
    {
        if (!$image) {
            return [];
        }
        $oldImage  = $user->avatar;
        $cloudPath = self::ROOT . '/' . $user->id;
        return config('cache.file_system_disk') === 'local'
            ? $this->fileSystemManager->updateFileFromLocal(
                $oldImage,
                $image,
                self::FIELD,
                self::ROOT,
            )
            : $this->fileSystemManager->updateFileFromCloud(
                $cloudServiceName,
                $oldImage,
                $image,
                $cloudPath,
                'public',
                self::FIELD
            );
    }

    public function delete(User $user, $cloudServiceName): void
    {
        config('cache.file_system_disk') == 'local' ?
            $this->fileSystemManager->deleteFileFromLocal($user->avatar) : 
            $this->fileSystemManager->deleteFileFromCloud($cloudServiceName, $user->avatar); 
    }
}
