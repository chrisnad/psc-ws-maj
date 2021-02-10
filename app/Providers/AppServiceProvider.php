<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $proxy_url    = env('PROXY_URL');
        $proxy_schema = env('PROXY_SCHEMA');
        $context_root = config('app.context_root');

        if(!empty($proxy_url)) {
            url()->forceRootUrl($proxy_url.'/'.$context_root.'/');
        }
        if(!empty($proxy_schema)) {
            url()->forceScheme($proxy_schema);
        }
    }
}
