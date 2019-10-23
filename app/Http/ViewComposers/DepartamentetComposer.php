<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\Department;
use Illuminate\Database\QueryException;
use Sentinel;

class DepartamentetComposer
{
    public $st;

    public function __construct()
    {
        try{
            $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->st=Department::select('departments.id','departments.department')->
            join('faculties','departments.faculty_id','faculties.id')->
            where('faculties.faculty',$faculty)->
            pluck('department','id')->toArray();
        }
        catch (QueryException $e)
        {
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në databazë!',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],400);
        }
    }

    public function compose(View $view)
    {
        $view->with('department',$this->st);
    }
}
