<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use BasicDashboard\Foundations\Domain\UserPackages\UserPackage;

class SendMapPricePackageIsExpiring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-map-price-package-is-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        $today = today();
        $twoDaysFromNow = $today->copy()->addDays(2);

        UserPackage::where('end_date', $twoDaysFromNow)
            ->where('status', '!=', 'expired')
            ->chunkById(100, function ($packages) {
                foreach ($packages as $package) {
                    \Log::info('User package expiring soon', [
                        'package_id' => $package->id,
                        'user_id' => $package->user_id,
                        'end_date' => $package->end_date,
                    ]);
                }
            });

        $this->info('Successfully logged user packages expiring in 2 days at ' . $today);
        $this->info('---');
    }
}
