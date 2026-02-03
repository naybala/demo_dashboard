<?php

namespace BasicDashboard\Web\Common;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaFileUploadController extends BaseController
{
    public function multiImageUploader(Request $request): JsonResponse    
    {
        try {
            $paths = [];
            $uploadPath = $request->path ? $request->path . Auth::id() : 'uploads';
            foreach ($request->file('images') as $image) {
                $path = Storage::putFile($uploadPath, $image);
                $paths[] = $path;
            }
            return $this->sendAjaxSuccess('Uploaded Successfully',$paths);
        } catch (\Exception $e) {
            return $this->sendAjaxError($e->getMessage());
        }
    }
}