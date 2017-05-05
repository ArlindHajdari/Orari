<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Setting;
use Sentinel;
use Illuminate\Database\QueryException;

class SettingsComposer
{
    public $percent;

    public function __construct()
    {
        try
        {
            if(Setting::where('user_id',Sentinel::getUser()->id)->first()){
                $settings = Setting::where('user_id',Sentinel::getUser()->id)->get()->toArray();
                $max = count($settings[0]);

                foreach($settings[0] as $key=>$value){
                    if($value == null || $value == 0){
                        unset($settings[0][$key]);
                    }
                }

                $this->percent = (count($settings[0])/$max)*100;
//
            }else{
                $this->percent = 0;
            }

        }
        catch(QueryException $e)
        {
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim ne databaze',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],500);
        }
    }
    public function compose(View $view)
    {
        $view->with('percent',$this->percent);
    }
}