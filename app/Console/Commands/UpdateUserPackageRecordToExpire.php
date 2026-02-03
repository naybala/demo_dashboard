<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\UserPackageStatus;
use BasicDashboard\Foundations\Domain\UserPackages\UserPackage;

class UpdateUserPackageRecordToExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-package-record-to-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update User Package Record To Expire';
    /**
     * The console command name.
     *
     * @var string
     * 
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = now();
        
        /**
         * @docs https://laravel.com/docs/12.x/queries#chunking-results
         */
        UserPackage::where('end_date', '<', $today)
            ->where('status', '!=', 'expired')
            ->chunkById(100, function ($packages) {
                foreach ($packages as $package) {
                    $package->update(['status' => UserPackageStatus::EXPIRED]);
                }
            });

        $this->info('Successfully updated expired user packages at '. today());
        $this->info('---');
        return Command::SUCCESS;
    }
}
