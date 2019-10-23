<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Subject;
use App\Models\SubjectLush;
use Illuminate\Database\QueryException;
use Sentinel;
use DB;

class LendetComposer
{
    protected $lendet,$subjectsLush;

    function __construct()
    {
        DB::enableQueryLog();
        try{
            $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->lendet = Subject::select('subjects.id','subjects.subject')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('faculties.faculty',$faculty)->pluck('subject','id')->toArray();

            $this->subjectsLush = SubjectLush::select('subject_lush.subject_id','subjects.subject')->
            join('subjects','subject_lush.subject_id','subjects.id')->
            join('departments','subjects.department_id','departments.id')->
            join('faculties','departments.faculty_id','faculties.id')->
            where('faculties.faculty',$faculty)->distinct()->pluck('subjects.subject','subject_lush.subject_id')->toArray();
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
        $view->with('lendet',$this->lendet)->with('subjects',$this->subjectsLush);
    }
}
