<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

final class CategoryComposer
{
    public function compose(View $view)
    {
        $view->with('viewCategories',DB::table('categories') ->whereNull('deleted_at')->where('is_show', 0)->pluck('name','id'));
    }
}