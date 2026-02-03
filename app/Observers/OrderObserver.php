<?php
namespace App\Observers;

use App\Enums\OrderStatus;
use App\Enums\UserPackageStatus;
use BasicDashboard\Foundations\Domain\Orders\Order;
use BasicDashboard\Foundations\Domain\UserPackages\Repositories\UserPackageRepositoryInterface;

class OrderObserver
{
    public function __construct(
        private UserPackageRepositoryInterface $userPackageRepositoryInterface
    ) {
    }
    public function saved(Order $order)
    {
        $package = $order->package;
        if ($order->status == OrderStatus::SUCCESS) {

            //There may be user package record for this order(with active, inactive or expired status)
            $checkPreviousOrder = $this->userPackageRepositoryInterface->checkUserPackageByOrder($order->id);
            if (! $checkPreviousOrder) {
                $this->userPackageRepositoryInterface->createUserPackage(
                    $order,
                    $package->type,
                    $package->duration
                );
            } else {
                $this->userPackageRepositoryInterface->ChangePackageStatusByOrder($order->id, UserPackageStatus::ACTIVE);
            }
        }

    }
}
