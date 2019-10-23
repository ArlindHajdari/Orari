<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\CP;
use Illuminate\Database\QueryException;

class SemesterComposer
{
    protected $semester;

    function __construct()
    {
        try{
            $this->semester = Cp::select('subjects.semester')->
            join('subjects', 'cps.subject_id', 'subjects.id')->
            join('departments', 'subjects.department_id', 'departments.id')->
            join('faculties', 'departments.faculty_id', 'faculties.id')->
            where('faculties.faculty', explode('_',user()->roles()->first()->slug)[1])->pluck('semester','semester')->toArray();
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
        $view->with('semesters',$this->semester);
    }
}
