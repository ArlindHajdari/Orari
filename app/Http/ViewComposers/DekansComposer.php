<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\User;
use Illuminate\Database\QueryException;
use DB;

class DekansComposer
{
    public $dekanet;

    public function __construct()
    {
        DB::enableQueryLog();
        try{
            $this->dekanet=User::select('')->where('cpa','Dekan')->pluck('academic_title'.'first_name'.' '.'last_name','id')->toArray();

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
//        dd(DB::getQueryLog());
        $view->with('dekanet',$this->dekanet);
    }
}
