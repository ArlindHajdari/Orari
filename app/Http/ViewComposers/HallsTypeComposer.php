<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Halltype;
use Illuminate\Database\QueryException;

class HallsTypeComposer
{
    public $halltypes;

    public function __construct()
    {
        try
        {
            $this->halltypes=Halltype::pluck('halltype','id')->toArray();
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
        
        $view->with('halltypes',$this->halltypes);
    }
}