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
        View::composer(['login','Menaxho.Dekanet.panel','Menaxho.Mesimdhenesi.panel'],'App\Http\ViewComposers\AcademicalTitleComposer');

        View::composer(['login','Menaxho.Dekanet.panel','Menaxho.Mesimdhenesi.panel'],
            'App\Http\ViewComposers\CPAsComposer');

        View::composer(['LendetPanel','Menaxho.Lendet.panel'],'App\Http\ViewComposers\LlojiLendesComposer');

        View::composer(['LendetPanel','Menaxho.Lendet.panel'],'App\Http\ViewComposers\DepartamentetComposer');

        View::composer('Menaxho.Dekanet.panel','App\Http\ViewComposers\RolesComposer');

        View::composer('Menaxho.Profesor-Lende.panel','App\Http\ViewComposers\Profesor');

        View::composer('Menaxho.Profesor-Lende.panel','App\Http\ViewComposers\AsistentComposer');

        View::composer('Menaxho.Profesor-Lende.panel','App\Http\ViewComposers\LendetComposer');
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
