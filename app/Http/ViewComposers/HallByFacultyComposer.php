<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Hall;
use Sentinel;
use DB;

class HallByFacultyComposer
{
    protected $HFC,$HFC2;

    public function __construct()
    {
        $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

        $this->HFC = Hall::select('halls.id','halls.hall')->leftJoin('faculties','halls.faculty_id','faculties.id')->where('faculties.faculty',$faculty)->pluck('hall','id')->toArray();

        $this->HFC2 = Hall::select('halls.id','halls.hall')->join('faculties','halls.faculty_id','faculties.id')->leftJoin('faculties as fa','halls.sec_faculty_id','fa.id')->where('faculties.faculty',$faculty)->orWhere('fa.faculty',$faculty)->pluck('hall','id')->toArray();
    }

    public function compose(View $view)
    {
        $view->with('HFC',$this->HFC)->with('HFC2',$this->HFC2);
    }
}