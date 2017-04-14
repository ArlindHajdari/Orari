<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\User;
use Illuminate\Database\QueryException;
use DB;

class Profesor
{
    protected $profesoret;

    function __construct()
    {
        try{
            $this->profesoret = User::select('users.id',DB::raw("concat(academic_titles.academic_title,users.first_name,' ',users.last_name) as full_name"))->join('academic_titles','users.academic_title_id','academic_titles.id')->join('cpas','users.cpa_id','cpas.id')->where('cpas.cpa','Ligjërues')->orWhere('cpas.cpa','Profesor')->pluck('full_name','id')->toArray();
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
        $view->with('profesoret',$this->profesoret);
    }
}