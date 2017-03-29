<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\AcademicalTitle;
use Illuminate\Database\QueryException;

class AcademicalTitleComposer
{
    protected $titles;

    function __construct()
    {
        try{
            $this->titles = AcademicalTitle::pluck('academical_title','id')->toArray();
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
        $view->with('academicalTitles',$this->titles);
    }
}