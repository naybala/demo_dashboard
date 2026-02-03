<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

final class CategoryComposer
{
    public function compose(View $view)
    {
        $view->with('viewCategories',DB::table('categories')->pluck('name','id'));
    }
}