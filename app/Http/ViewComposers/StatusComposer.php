<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Models\Status;
use App\Models\StatusAcademicTitle;
use Illuminate\Database\QueryException;

class StatusComposer
{
    public $statuses;
    public $status_academicTitle;

    public function __construct()
    {
        try{
            $this->status_academicTitle = StatusAcademicTitle::select('status.name','status.id')->
            join('status','status_academic_titles.status_id','status.id')->pluck('status.name','status.id')->toArray();
            $this->statuses=Status::pluck('name','id')->toArray();
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
        $view->with('statuses',$this->statuses)->with('statusAcademicTitle',$this->status_academicTitle);
    }
}
