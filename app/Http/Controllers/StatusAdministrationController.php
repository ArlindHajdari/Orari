<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;

class StatusAdministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $messages = [
            'status.required' => 'Statusi eshte i nevojshem!',
            'status.alpha_num' =>'Statusi nuk duhet te kete simbole!',
            'status.min' => 'Statusi duhet te permbaj se paku :min karaktere!'
        ];

        $validation = Validator::make($request->all(),[
            'status'=>'required|alpha_num|min:4'
        ],$messages);

        if($validation->fails()){
            return response()->json([
                'fails'=> true,
                'errors'=> $validation->errors()->first('status')
            ],400);
        }

        $status = new Status;
        $status->name = $request->status;
        if($status->save()){
            return response()->json([
                'success'=>true,
                'title'=>'Sukses',
                'msg'=>'Te dhenat u ndryshuan me sukses!'
            ],200);
        }else{
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim',
                'msg'=>'Te dhenat nuk arriten te ndryshohen!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $status = Status::select('name','id')->where('name','like','%'.$request->search.'%')->paginate(10);

        return view('Menaxho.Statusi.statusiAdministration',['statuses'=>$status]);
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
        $messages = [
            'status.required' => 'Statusi eshte i nevojshem!',
            'status.alpha_num' =>'Statusi nuk duhet te kete simbole!',
            'status.min' => 'Statusi duhet te permbaj se paku :min karaktere!'
        ];

        $validation = Validator::make($request->all(),[
            'status'=>'required|alpha_num|min:4'
        ],$messages);

        if($validation->fails()){
            return response()->json([
                'fails'=> true,
                'errors'=> $validation->errors()->first('status')
            ],400);
        }

        $status = Status::find($id);
        $status->name = $request->status;
        if($status->save()){
            return response()->json([
                'success'=>true,
                'title'=>'Sukses',
                'msg'=>'Te dhenat u ndryshuan me sukses!'
            ],200);
        }else{
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim',
                'msg'=>'Te dhenat nuk arriten te ndryshohen!'
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
        if(Status::find($id)->delete()){
            return redirect('statusAdministration');
        }else{
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim',
                'msg'=>'Te dhenat nuk arriten te fshihen!'
            ],500);
        }
    }
}
