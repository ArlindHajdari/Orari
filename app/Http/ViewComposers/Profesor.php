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
            $this->profesoret = User::select('users.id',DB::raw("concat(academic_titles.academic_title,users.first_name,' ',users.last_name) as full_name"))->join('academic_titles','users.academic_title_id','academic_titles.id')->join('cpas','users.cpa_id','cpas.id')->join('role_users','role_users.user_id','users.id')->join('roles','role_users.role_id', 'roles.id')->where(function($query){
                $query->orWhere('cpas.cpa','Ligjërues');
                $query->orWhere('cpas.cpa','Profesor');
                $query->orWhere('cpas.cpa','Dekan');
            })->where('roles.slug','<>','admin')->pluck('full_name','id')->toArray();
    }

    public function compose(View $view)
    {
        try{
            $view->with('profesoret',$this->profesoret);
        }catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në databazë!',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],500);
        }
            catch(\ErrorException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në databazë!',
                'msg'=>'Ju lutem kontaktoni mirëmbajtësit e faqes!'
            ],500);
        }
    }
}