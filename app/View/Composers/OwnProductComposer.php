<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

final class OwnProductComposer
{
    public function compose(View $view)
    {
        $ownProducts = DB::table('own_products')
            ->leftJoin('units', 'own_products.unit_id', '=', 'units.id')
            ->select('own_products.name','own_products.id','own_products.unit_id','units.name as unit_name','own_products.price','own_products.profit','own_products.investment')
            ->whereNull('own_products.deleted_at')
            ->get();


        $view->with('viewOwnProducts', $ownProducts->pluck('name', 'id')->toArray());
        $view->with('ownProductsData', $ownProducts->keyBy('id')->toJson());
    }

}