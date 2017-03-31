<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['login','Menaxho.Dekanet.panel'],'App\Http\ViewComposers\AcademicalTitleComposer');

        View::composer(['login','Menaxho.Dekanet.panel'],'App\Http\ViewComposers\CPAsComposer');

        View::composer('Menaxho.Dekanet.panel','App\Http\ViewComposers\RolesComposer');

//        View::composer('Menaxho.Dekanet.panel','App\Http\ViewComposers\DekansComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
