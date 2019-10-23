<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\Faculty;
use Illuminate\Database\QueryException;
use Sentinel;

class FacultyComposer
{
    public $faculty,$secFaculty;

    public function __construct()
    {
        try{
            $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

            $this->faculty=Faculty::pluck('faculty','id')->toArray();

            $this->secFaculty=Faculty::where('faculty','<>',$faculty)->pluck('faculty','id')->toArray();
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në databazë!',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],400);
        }
    }

    public function compose(View $view)
    {
        $view->with('faculty',$this->faculty)->with('secfaculty',$this->secFaculty);
    }
}
