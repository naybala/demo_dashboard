<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commitTransaction()
    {
        DB::commit();
    }

    public function rollBackTransaction()
    {
        DB::rollBack();
    }

    public function LogError($message,$e=null)
    {
        Log::error($message. ": " . $e->getMessage(), ['exception' => $e]);
    }
}
