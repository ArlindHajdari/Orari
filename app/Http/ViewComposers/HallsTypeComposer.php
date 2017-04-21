<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Halltype;
use Illuminate\Database\QueryException;

class HallsTypeComposer
{

    public $halltypes;


    public $halls;
    public $ditet=['Hënë'=>'Hënë','Martë'=>'Martë','Mërkurë'=>'Mërkurë','Enjte'=>'Enjte','Premte'=>'Premte',
        'Shtunë'=>'Shtunë'];
    public $oraprej=['8:00'=>'8:00','8:15'=>'8:15','8:30'=>'8:30','8:45'=>'8:45','9:00'=>'9:00','9:15'=>'9:15','9:30'=>'9:30','9:45'=>'9:45','10:00'=>'10:00','10:15'=>'10:15','10:30'=>'10:30','10:45'=>'10:45','11:00'=>'11:00','11:15'=>'11:15','11:30'=>'11:30','11:45'=>'11:45','12:00'=>'12:00','12:15'=>'12:15','12:30'=>'12:30','12:45'=>'12:45','13:00'=>'13:00'];
    public $oraderi=['8:00'=>'8:00','8:15'=>'8:15','8:30'=>'8:30','8:45'=>'8:45','9:00'=>'9:00','9:15'=>'9:15','9:30'=>'9:30','9:45'=>'9:45','10:00'=>'10:00','10:15'=>'10:15','10:30'=>'10:30','10:45'=>'10:45','11:00'=>'11:00','11:15'=>'11:15','11:30'=>'11:30','11:45'=>'11:45','12:00'=>'12:00','12:15'=>'12:15','12:30'=>'12:30','12:45'=>'12:45','13:00'=>'13:00'];

    public function __construct()
    {
        try
        {
            $this->halltypes=Halltype::pluck('halltype','id')->toArray();
        }
        catch(QueryException $e)
        {
            return response()-json([
                'fails'=>true,
                'title'=>'Gabim ne databaze',
                'msg'=>'Të dhëna të caktuara nuk mundën të nxirren nga databaza!'
            ],500);
        }
    }
    public function compose(View $view)
    {
        $view->with('halltypes',$this->halltypes);
    }
}