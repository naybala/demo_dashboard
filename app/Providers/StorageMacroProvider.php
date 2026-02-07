<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class StorageMacroProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Note This Generate Presigned Url Only Support S3 Object Storage
         */
        Storage::macro('generatePresignedUrl',function ($count, $filePath): array{
            $links = [];
    
            for ($i = 0; $i < $count; $i++) {
                $path = $filePath . '/' . Str::random(15);
                $url = Storage::temporaryUploadUrl(
                    $path,
                    now()->addMinutes(20)
                );
                $links[] = [
                    'url' => $url,     // presigned URL
                    'path' => $path,   // path to be stored in the database
                ];
            }
    
            return $links;
        });

        Storage::macro('uploadFileToCloud', function ($disk,$file, $path,$privacy,$columnKey): array{
            $fileUrl = $file ? Storage::disk($disk)->putFile($path, $file, $privacy) : null; 
            $data        = [
                $columnKey => $fileUrl,
            ];
            return $data;
        });

        Storage::macro('uploadFileToLocal', function ($file, $path,$columnKey): array{
            $fileUrl = $file ? $file->store($path,'public') : null;
            $data = [
                $columnKey => "/storage/".$fileUrl,
            ];
            return $data;
        });

        Storage::macro('uploadFilesToLocal',function(array $files,$path,$columnKey):array{
            $columnKey = [];
            foreach ($files as $file) {
                $path = $file->store($path, 'public');
                $columnKey[] = "/storage/" . $path;
            }
            return $columnKey;
        });

        Storage::macro('updateFileFromCloud',function($disk,$oldFileUrl,$newFile,$path,$privacy,$columnKey):array{
             $fileUrl = null;
            if ($newFile) {
                $oldFileUrl == null ? '' : Storage::disk($disk)->delete($oldFileUrl);
                $fileUrl = Storage::disk($disk)->putFile($path, $newFile, $privacy);
            }
            $data = [
                $columnKey => $fileUrl == null ? $oldFileUrl : $fileUrl,
            ];
            return $data;
        });

        Storage::macro('updateFileFromLocal',function($oldFileUrl,$newFile,$columnKey,$path):array{
            $fileUrl = null;
            if ($newFile) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldFileUrl));
                $fileUrl = $newFile->store($path,'public');
                $fileUrl = "/storage/$fileUrl";
            }
            $data = [
                $columnKey => $fileUrl == null ? $oldFileUrl : $fileUrl,
            ]; 
            return $data;
        });

        Storage::macro('deletFileFromLocal',function($fileUrl):void{
            Storage::disk('public')->delete(str_replace('storage/', '', $fileUrl));
        });

        Storage::macro('deleteFilesFromLocal',function(array $oldFiles,$existingFiles):void{
            foreach ($oldFiles as $oldFile) {
                if (!in_array($oldFile, $existingFiles)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
        });

        Storage::macro('forceDeleteFilesFromLocal',function(array $oldFiles):void{
            foreach ($oldFiles as $oldFile) {
                Storage::disk('public')->delete($oldFile);
            }
        });

        Storage::macro('deleteFileFromCloud',function($disk,$fileUrl):void{
            Storage::disk($disk)->delete($fileUrl);
        });
    }
}
