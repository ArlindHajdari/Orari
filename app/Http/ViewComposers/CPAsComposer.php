<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\Cpa;
use Illuminate\Database\QueryException;

class CPAsComposer
{
    public $cpas;

    public function __construct()
    {
        $this->cpas=Cpa::pluck('cpa','id');
    }

    public function compose(View $view)
    {
        try{
            $view->with('cpas',$this->cpas->toArray());
        }
        catch(QueryException $e){
            return response()->json([
            'fails'=>true,
            'title'=>'Gabim në databazë!',
            'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],400);
        }
    }
}
