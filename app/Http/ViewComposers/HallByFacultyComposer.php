<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\User;
use App\Models\Hall;
use Sentinel;
use Illuminate\Database\QueryException;
use DB;

class HallByFacultyComposer
{
    protected $HFC;

    function __construct()
    {
        try{

            $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->HFC = Hall::select('halls.id','halls.hall')->join('faculties','halls.faculty_id','faculties.id')->where('faculties.faculty',$faculty)->pluck('hall','id')->toArray();

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
        $view->with('HFC',$this->HFC);
    }
}