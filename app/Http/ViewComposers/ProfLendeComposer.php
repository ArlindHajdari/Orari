<?php

namespace App\Http\ViewComposers;

use App\Models\Cp;
use Illuminate\View\View;
use Sentinel;
use DB;

class ProfLendeComposer
{
    protected $profSub;

    function __construct()
    {
            $fakultet = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->profSub = Cp::select(DB::raw('concat(subjects.id,"-",cps.id) as id'),'subjects.subject')->join('subjects','cps.subject_id','subjects.id')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('faculties.faculty',$fakultet)->pluck('subject','id')->toArray();
    }

    public function compose(View $view)
    {
        $view->with('profSub',$this->profSub);
    }
}