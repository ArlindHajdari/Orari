<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Hall;
use Illuminate\Database\QueryException;

class HallsTypeComposer
{
    public $halls;

    public function __construct()
    {
        try
        {
            $this->halls=Hall::pluck('hall','id')->toArray();
        }
        catch(QueryException $e)
        {
            return response()-json([
                'fails'=>true,
                'title'=>'Gabim ne databaze',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],400);
        }
    }
    public function compose(View $view)
    {
        dd($this->halls);
        $view->with('halls',$this->halls);
    }
}