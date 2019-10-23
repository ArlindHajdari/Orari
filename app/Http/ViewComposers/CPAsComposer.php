<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\Cpa;
use Illuminate\Database\QueryException;

class CPAsComposer
{
    public $cpas,$cpas_noDekan;

    public function __construct()
    {
        try{
            $this->cpas=Cpa::pluck('cpa','id')->toArray();
            $this->cpas_noDekan=Cpa::where('cpa','<>','Dekan')->orWhere('cpa','<>','dekan')->pluck('cpa','id')
                ->toArray();
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
        $view->with('cpas',$this->cpas)->with('cpas_nodekan',$this->cpas_noDekan);
    }
}
