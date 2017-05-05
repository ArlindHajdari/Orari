<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sentinel;
use App\Models\Setting;
use Validator;
use DB;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'max_hour_day_professor'=>'bail|numeric|max:6|nullable',
                'max_hour_day_assistant'=>'bail|numeric|max:6|nullable'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=> $validator->getMessageBag()->toArray()
                ],400);
            }

            $setting = Setting::find($id);
            $setting->max_hour_day_professor = $request->max_hour_day_professor;
            $setting->max_hour_day_assistant = $request->max_hour_day_assistant;

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
