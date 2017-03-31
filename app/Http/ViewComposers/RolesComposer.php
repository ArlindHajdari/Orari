<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Role;
use Illuminate\Database\QueryException;

class RolesComposer
{
    protected $roles;

    public function __construct()
    {
        try{
            $this->roles = Role::pluck('name','id')->toArray();
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
        $view->with('roles',$this->roles);
    }
}