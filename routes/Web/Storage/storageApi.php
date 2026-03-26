<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

//for description rich editor toupload to digitalocean directly after user click and upload photo.
Route::post('upload/image', function (Request $request) {
    $path    = request()->input('path');
    $file    = request()->file('image');
    $url     = uploadImageToDigitalOcean($file, $path); //get file path that store in digitalocean
    $fullURL = config('filesystems.disks.digitalocean.endpoint') . '/' . $url;
    return response()->json([
        'data' => 'succeess',
        'code' => 200,
        'url'  => $fullURL,
    ]);
})->name('uploadImage');

Route::post('upload/image/local', function (Illuminate\Http\Request $request) {
    if (!$request->hasFile('image')) {
        return response()->json(['error' => 'No image uploaded'], 400);
    }
    $path = $request->input('path', 'uploads');
    $file = $request->file('image');
    $url  = uploadImageToLocal($file, $path);
    if (!$url) {
        return response()->json(['error' => 'Failed to save image locally'], 500);
    }
    return response()->json([
        'data' => 'success',
        'code' => 200,
        'url'  => $url,
    ]);
})->name('uploadImageLocal');


Route::post('upload/image/local/quill', function (Illuminate\Http\Request $request) {
    if (!$request->hasFile('image')) {
        return response()->json(['error' => 'No image uploaded'], 400);
    }
    $path = $request->input('path', 'uploads/quill');
    $file = $request->file('image');
    $url  = uploadImageToLocal($file, $path);
    if (!$url) {
        return response()->json(['error' => 'Failed to save image locally'], 500);
    }
    return response()->json([
        'data' => 'success',
        'code' => 200,
        'url'  => $url,
    ]);
})->name('uploadImageLocalQuill');

Route::post('upload/presigned-url', function (Illuminate\Http\Request $request) {
    $count = $request->input('count', 1);
    $path = $request->input('path', 'avatars');
    
    $links = \Illuminate\Support\Facades\Storage::generatePresignedUrl($count, $path);
    
    return response()->json([
        'data'  => 'success',
        'code'  => 200,
        'links' => $links,
    ]);
})->name('uploadPresignedUrl');