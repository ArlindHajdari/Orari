<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Subject;
use Illuminate\Database\QueryException;
use Sentinel;
use DB;

class LendetComposer
{
    protected $lendet;

    function __construct()
    {
        DB::enableQueryLog();
        try{
            $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->lendet = Subject::select('subjects.id','subjects.subject')->join('department_subjects','subjects.id','department_subjects.subject_id')->join('departments','department_subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('faculty',$faculty)->pluck('subject','id')->toArray();

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
        $view->with('lendet',$this->lendet);
    }
}