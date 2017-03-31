<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\User;
use Illuminate\Database\QueryException;

class DekansComposer
{
    public $dekanet;

    public function __construct()
    {
        try{
            $this->dekanet=User::paginate(20);
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
        $view->with('data',$this->dekanet);
    }
}
