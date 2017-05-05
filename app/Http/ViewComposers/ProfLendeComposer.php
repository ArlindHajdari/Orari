<?php

namespace App\Http\ViewComposers;

use App\Models\Cp;
use Illuminate\View\View;
use App\Models\User;
use Sentinel;
use Illuminate\Database\QueryException;
use DB;

class ProfLendeComposer
{
    protected $profSub;

    function __construct()
    {
        try{

            $fakultet = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->profSub = Cp::select('subjects.id','subjects.subject')->join('subjects','cps.subject_id','subjects.id')->join('department_subjects','subjects.id','department_subjects.subject_id')->join('departments','department_subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('faculties.faculty',$fakultet)->pluck('subject','id')->toArray();

        }catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në databazë!',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],400);
        }
    }

    public function compose(View $view)
    {
        $view->with('profSub',$this->profSub);
    }
}