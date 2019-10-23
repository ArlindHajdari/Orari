<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Lush;
use App\Models\SubjectLush;
use Sentinel;
use DB;

class LUSHComposer
{
    protected $lush,$subject_lush;

    function __construct()
    {
        $this->subject_lush = SubjectLush::select('lush.lush','lush.id')->
        join('lush','subject_lush.lush_id','lush.id')->pluck('lush.lush','lush.id')->toArray();
        $this->lush = Lush::pluck('lush','id')->toArray();
    }

    public function compose(View $view)
    {
        $view->with('lush',$this->lush)->with('subject_lush',$this->subject_lush);
    }
}
