<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Hall;
use Illuminate\Database\QueryException;

class HallsComposer
{
    protected $halls;

    function __construct()
    {
        try{
            $this->halls = Hall::pluck('hall','id')->toArray();

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
        $view->with('halls',$this->halls);
    }
}