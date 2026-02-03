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
        // $randomNumber = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, config('numbers.hash_length'));
        // $randomLetter2 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, config('numbers.hash_length'));
        // $encryptId = strtr(base64_encode($plainText), '---', '===');
        // if ($static) {
        //     return $static . $encryptId . $static;
        // } else {
        //     return rand(10000, 99999) . $encryptId . rand(10000, 99999);
        // }
        return $plainText;
    }
}

if (! function_exists('customDecoder')) {
    function customDecoder($b64Text)
    {
        return $b64Text;
        // $decryptId = substr($b64Text, 0, "-" . config('numbers.hash_length'));
        // $decryptId = substr($decryptId, config('numbers.hash_length'));
        // return base64_decode(strtr($decryptId, '===', '---'));
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
        $uploadMedia = Storage::disk('digitalocean')->putFile($directory, $file, $privacy);
        return $uploadMedia ?: null;
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
