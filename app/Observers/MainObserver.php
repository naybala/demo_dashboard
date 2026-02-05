<?php

namespace App\Observers;

use BasicDashboard\Foundations\Domain\Audits\Audit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MainObserver
{
    public function __construct(private Audit $audit)
    {

    }

    public function prepareDataAndSave($oldData, $newData, $event, $model)
    {
        $data = [
            'model' => $model,
            'event' => $event,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'created_by' => Auth::user()->id ?? null,
            'created_at' => Carbon::now(),
        ];
        $this->audit->create($data);
    }

}
