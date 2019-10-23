<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\User;
use Illuminate\Database\QueryException;
use DB;
use Sentinel;

class DekansComposer
{
    public $dekanet;

    public function __construct()
    {
        DB::enableQueryLog();
        try{
            $this->dekanet = User::select(DB::raw("CONCAT(academic_titles.academic_title,' ',users.first_name,' ',users.last_name) AS fullname"),'users.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->join('cpas','users.cpa_id','cpas.id')->where('cpa','Dekan')->where('users.id','<>',Sentinel::getUser()->id)->pluck('fullname', 'id')->toArray();

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
