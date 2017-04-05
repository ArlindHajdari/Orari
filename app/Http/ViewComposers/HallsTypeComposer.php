<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Halltype;
use Illuminate\Database\QueryException;

class HallsTypeComposer
{
    public $halls;

    public function __construct()
    {
        try
        {
            $this->halls=Halltype::pluck('halltype','id')->toArray();
        }
        catch(QueryException $e)
        {
            return response()-json([
                'fails'=>true,
                'title'=>'Gabim ne databaze',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],500);
        }
    }
    public function compose(View $view)
    {
        
        $view->with('halls',$this->halls);
    }
}