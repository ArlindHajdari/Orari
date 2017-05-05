<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Cpa;
use DB;
use View;
use Validator;

class CpaController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'cpa'=>'bail|required|alpha|max:60|unique:cpas'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            } else{
                $cpa = new Cpa;
                $cpa->cpa = $request->cpa;

                if($cpa->save()){
                    return response()->json([
                        'success'=>true,
                        'title'=>'Sukses',
                        'msg'=>'Te dhenat u regjistruan me sukses!'
                    ],200);
                }
            }
        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në server',
                'msg'=>$e->getMessage()
            ],500);
        }
    }

    public function show(Request $request)
    {
        DB::enableQueryLog();
        try{
            $cpa = Cpa::where('cpa','like','%'.$request->search.'%')->paginate(15);

            return view('Menaxho.CPA.panel')->with('cpa',$cpa);
        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në server',
                'msg'=>$e->getMessage()
            ],500);
        }
    }

    public function edit(Request $request,$id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'cpa' => 'bail|required|alpha|max:60'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $cpa = Cpa::find($id);
            $cpa->cpa = $request->cpa;
            if($cpa->save()){
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
                'title'=>'Gabim në server',
                'msg'=>$e->getMessage()
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $cpa = Cpa::find($id);
        $cpa->delete();
        return redirect('cpaPanel');
    }
}
