<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\AcademicTitle;
use Illuminate\Database\QueryException;
use App\Models\StatusAcademicTitle;

class AcademicalTitleComposer
{
    protected $titles;
    protected $titlesFromStatus;

    function __construct()
    {
        try{
            $this->titlesFromStatus = StatusAcademicTitle::select('academic_titles.academic_title','status_academic_titles.academic_title_id')->
            join('academic_titles','status_academic_titles.academic_title_id','academic_titles.id')->distinct()->pluck('academic_titles.academic_title','status_academic_titles.academic_title_id')->toArray();

            $this->titles = AcademicTitle::pluck('academic_title','id')->toArray();
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
        $view->with('academicalTitles',$this->titles)->with('titlesFromStatus',$this->titlesFromStatus);
    }
}
