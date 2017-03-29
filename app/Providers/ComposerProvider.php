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
        View::composer(['login'],'App\Http\ViewComposers\AcademicalTitleComposer');

        View::composer(['login'],'App\Http\ViewComposers\CPAsComposer');

        View::composer(['dekanRegister'],'App\Http\ViewComposers\CPAsComposer');
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
