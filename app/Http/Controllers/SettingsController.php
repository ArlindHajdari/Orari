<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sentinel;
use App\Models\Setting;
use Validator;
use DB;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();
        try{
            $data = Setting::where('user_id',Sentinel::getUser()->id)->paginate(10);
//        dd(DB::getQueryLog());
            return view('Menaxho.Rregullat.panel')->with('data',$data);
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'summer_semester'=>'bail|required|string',
                'winter_semester'=>'bail|required|string',
                'max_hour_day_professor'=>'bail|numeric|nullable',
                'max_hour_day_assistant'=>'bail|numeric|nullable',
                'start_schedule_time'=>'required|date_format:"H:i:s"',
                'end_schedule_time'=>'required|date_format:"H:i:s"|after:start_schedule_time'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=> $validator->getMessageBag()->toArray()
                ],400);
            }

            $setting = Setting::find($id);
            $setting->start_summer_semester = Carbon::parse(explode(' ',trim($request->summer_semester))[0])->toDateString();
            $setting->end_summer_semester = Carbon::parse(explode(' ',trim($request->summer_semester))[2])->toDateString();
            $setting->start_winter_semester = Carbon::parse(explode(' ',trim($request->winter_semester))[0])->toDateString();
            $setting->end_winter_semester = Carbon::parse(explode(' ',trim($request->winter_semester))[2])->toDateString();
            $setting->start_schedule_time = Carbon::parse($request->start_schedule_time)->toTimeString();
            $setting->end_schedule_time = Carbon::parse($request->end_schedule_time)->toTimeString();
            $setting->max_hour_day_lecture = $request->max_hour_day_professor;
            $setting->max_hour_day_exercise = $request->max_hour_day_assistant;

            if($setting->save()){
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg'=>'Të dhënat u ndryshuan me sukses!'
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim',
                    'msg'=>'Të dhënat nuk u ndryshuan!'
                ],400);
            }
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim ne server!',
                'msg'=>'Procesi nuk mundi të përfundohet'
            ],500);
        }
    }

    public function destroy($id)
    {
        //
    }
}
