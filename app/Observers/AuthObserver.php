<?php

namespace App\Observers;

use BasicDashboard\Foundations\Domain\Audits\Audit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthObserver
{
    public function __construct(private Audit $audit)
    {

    }

    public function AuthDetection($event)
    {
        $data = [
            'model' => null,
            'event' => $event,
            'old_data' => null,
            'new_data' => null,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ];
        $this->audit->create($data);
    }

}
