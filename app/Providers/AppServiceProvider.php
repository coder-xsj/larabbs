<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use App\Observers\TopicObserver;
//use App\Models\Topic;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        \App\Models\Topic::observe(\App\Observers\TopicObserver::class);
    }
}
