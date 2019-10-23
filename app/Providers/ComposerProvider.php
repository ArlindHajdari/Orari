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
        View::composer(['login','Menaxho.Dekanet.panel','Menaxho.Mesimdhenesi.panel','Menaxho.Statusi.statusi'],'App\Http\ViewComposers\AcademicalTitleComposer');

        View::composer(['login','Menaxho.Dekanet.panel','Menaxho.Mesimdhenesi.panel','Menaxho.CpaLush.panel'], 'App\Http\ViewComposers\CPAsComposer');

        View::composer(['Menaxho.Lendet.panel'],'App\Http\ViewComposers\LlojiLendesComposer');

        View::composer(['Menaxho.Lendet.panel'],'App\Http\ViewComposers\DepartamentetComposer');

        View::composer('Menaxho.Dekanet.panel','App\Http\ViewComposers\RolesComposer');

        View::composer('Menaxho.Profesor-Lende.panel','App\Http\ViewComposers\Profesor');

        View::composer('Menaxho.Profesor-Lende.panel','App\Http\ViewComposers\AsistentComposer');

        View::composer(['Menaxho.Profesor-Lende.panel','Menaxho.Grupet.groupSubjectLush'],'App\Http\ViewComposers\LendetComposer');

        View::composer('Menaxho.Sallat.panel','App\Http\ViewComposers\HallsTypeComposer');

        View::composer(['Menaxho.Departamentet.panel','Menaxho.Sallat.panel'],'App\Http\ViewComposers\FacultyComposer');

        View::composer(['Menaxho.Statusi.statusi','Menaxho.Mesimdhenesi.panel'],'App\Http\ViewComposers\StatusComposer');

        View::composer('Menaxho.Kontakti.contact','App\Http\ViewComposers\DekansComposer');

        View::composer('Menaxho.Kontakti.contact','App\Http\ViewComposers\HallsTypeComposer');

        View::composer('Menaxho.Orari.scheduler','App\Http\ViewComposers\Profesor');

        View::composer('Menaxho.Orari.scheduler','App\Http\ViewComposers\ProfLendeComposer');

        View::composer('Menaxho.Orari.scheduler','App\Http\ViewComposers\SemesterComposer');

        View::composer(['Menaxho.Orari.scheduler','Menaxho.Kontakti.contact','Menaxho.Sallat.secPanel'],'App\Http\ViewComposers\HallByFacultyComposer');

        View::composer('Menaxho.Sallat.secPanel','App\Http\ViewComposers\FacultyComposer');

        View::composer(['Menaxho.CpaLush.panel','Menaxho.Lendet.panel'],'App\Http\ViewComposers\LUSHComposer');

        View::composer(['Menaxho.Orari.scheduler','Menaxho.Grupet.groupSubjectLush'],'App\Http\ViewComposers\GroupsComposer');

        View::composer(['layouts.header','Menaxho.Rregullat.panel'],'App\Http\ViewComposers\SettingsComposer');
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
