<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LushCpa;
use DB;
use Validator;

class CpaLushController extends Controller
{

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cpa_id' => 'required|numeric|min:1',
            'lush_id' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
        }

        if(!LushCpa::where('cpa_id',$request->cpa_id)->where('lush_id',$request->lush_id)->exists()){
            $lushcpa = new LushCpa;
            $lushcpa->cpa_id = $request->cpa_id;
            $lushcpa->lush_id = $request->lush_id;

            if($lushcpa->save()){
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg'=>'Te dhenat u regjistruan me sukses'
                ],200);
            }
        }else{
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim',
                'msg'=>'Këto të dhëna janë regjistruar një herë!'
            ],500);
        }
    }

    public function show(Request $request)
    {
        try{
            $data = LushCpa::select('cpas.id as cpa_id','cpas.cpa','lush.lush','lush.id as lush_id')->
            join('cpas','lush_cpa.cpa_id','cpas.id')->join('lush','lush_cpa.lush_id','lush.id')->
            where('cpas.cpa','like','%'.$request->search.'%')->
            orWhere('lush.lush','like','%'.$request->search.'%')->paginate(10);

            return view('Menaxho.CpaLush.panel',['data'=>$data]);
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin!'
            ],500);
        }

    }

    public function edit(Request $request,$cpa_id,$lush_id)
    {
        try{
            $validation = Validator::make($request->all(),[
                'cpa_id' => 'required|numeric|min:1|exists:cpas,id',
                'lush_id' => 'required|numeric|min:1|exists:lush,id'
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            if(LushCpa::where('cpa_id',$cpa_id)->where('lush_id',$lush_id)->update(['cpa_id'=>$request->cpa_id, 'lush_id'=>$request->lush_id])){
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg' => 'Të dhënat u ndryshuan me sukses!'
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim internal',
                    'msg'=>'Ju lutemi kontaktoni mbështetësit e faqes!'
                ],500);
            }

        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin'
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($cpa_id, $lush_id)
    {
        LushCpa::where('cpa_id',$cpa_id)->where('lush_id',$lush_id)->delete();
        return redirect('cpalushPanel');
    }
}
