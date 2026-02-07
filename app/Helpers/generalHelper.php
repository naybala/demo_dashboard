<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

if (! function_exists('changeParam')) {
    function changeParam($route, $param): string
    {
        return route($route, array_merge(request()->query(), $param));
    }
}

if (! function_exists('customEncoder')) {
    function customEncoder($plainText, $static = 0)
    {
        $encryptId = strtr(base64_encode($plainText), '---', '===');
        if ($static) {
            return $static . $encryptId . $static;
        } else {
            return rand(10000, 99999) . $encryptId . rand(10000, 99999);
        }
        // return $plainText;
    }
}

if (! function_exists('customDecoder')) {
    function customDecoder($b64Text)
    {
        $decryptId = substr($b64Text, 0, "-" . config('numbers.hash_length'));
        $decryptId = substr($decryptId, config('numbers.hash_length'));
        return base64_decode(strtr($decryptId, '===', '---'));
        // return $b64Text;
    }
}

/**
 * Check Permissions
 */
if (! function_exists('permissionCheck')) {
    function permissionCheck(array $checkPermissions): bool
    {
        $sessionPermission = Session::get('permission_key'); //"manage users,create users,manage countries,create countries"
        $sessionPermission = explode(",", $sessionPermission);
        $getSameArrays     = array_intersect($checkPermissions, $sessionPermission);
        if (count($getSameArrays) > 0) {
            return true;
        }
        return false;
    }

}

if (! function_exists('uploadImageToDigitalOcean')) {
    function uploadImageToDigitalOcean($file, $directory, $privacy = 'public'): String
    {
        \Illuminate\Support\Facades\Log::info("uploadImageToDigitalOcean path: " . $directory);
        $uploadMedia = Storage::disk('digitalocean')->putFile($directory, $file, $privacy);
        \Illuminate\Support\Facades\Log::info("uploadImageToDigitalOcean saved path: " . $uploadMedia);
        return $uploadMedia ?: null;
    }
}

if (! function_exists('uploadImageToLocal')) {
    function uploadImageToLocal($file, $directory): ?String
    {
        \Illuminate\Support\Facades\Log::info("uploadImageToLocal path: " . $directory);
        $path = Storage::disk('public')->putFile($directory, $file);
        
        if (!$path) {
            \Illuminate\Support\Facades\Log::error("uploadImageToLocal FAILED for path: " . $directory);
            return null;
        }
        
        \Illuminate\Support\Facades\Log::info("uploadImageToLocal saved path: " . $path);
        return '/storage/' . $path;
    }
}

if (! function_exists('retrievePublicFiles')) {
    function retrievePublicFiles(array | null $array): array
    {
        $array = $array == null ? [] : $array;
        if (isset($array) && count($array) > 0) {
            return array_map(fn($path) => Storage::disk('digitalocean')->url($path), $array);
        } else {
            return [];
        }
    }
}

if (! function_exists('generateUniqueCode')) {
    function generateUniqueCode(string $preChar, int $userId): string
    {
        $paddedUserId = str_pad($userId, 4, '0', STR_PAD_LEFT);
        $dateTime     = now()->format('YmdHis');
        return $preChar . $paddedUserId . $dateTime;
    }

}
