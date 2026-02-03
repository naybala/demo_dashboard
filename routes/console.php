<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SendMapPricePackageIsExpiring;
use App\Console\Commands\UpdateUserPackageRecordToExpire;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Schedule::command(UpdateUserPackageRecordToExpire::class)->dailyAt('01:00')->appendOutputTo(storage_path('logs/package-updater.log'));
// Schedule::command(SendMapPricePackageIsExpiring::class)->everyMinute()->appendOutputTo(storage_path('logs/expire-remind.log'));