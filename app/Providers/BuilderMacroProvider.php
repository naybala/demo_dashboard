<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class BuilderMacroProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('searchFilter', fn(string $column, mixed $value) => $this->when($value, fn($query) => $query->orWhere($column,$value)));
        Builder::macro('andSearchFilter', fn(string $column, mixed $value) => $this->when($value, fn($query) => $query->where($column,$value)));
        Builder::macro('searchLikeFilter', fn(string $column, mixed $value) => $this->when($value, fn($query) => $query->orWhere($column, 'LIKE',"%$value%")));
    }
}
