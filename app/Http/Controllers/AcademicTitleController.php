<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\AcademicTitle;
use DB;
use View;
use Validator;

class AcademicTitleController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'academic_title' => 'bail|required|max:50|unique:academic_titles'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $acadmicTitle = new AcademicTitle;
            $acadmicTitle->academic_title = $request->academic_title;

            if($acadmicTitle->save()){
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg'=>'Te dhenat u regjistruan me sukses!'
                ],200);
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
        try {
            $academicTitle = AcademicTitle::where('academic_title','like','%'.$request->search.'%')->paginate(15);

            return view('Menaxho.AcademicTitles.panel')->with('academicTitle',$academicTitle);
        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim ne server',
                'msg'=>$e->getMessage()
            ],500);
        }
    }

    public function edit(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'academic_title' => 'bail|required|max:50'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $academicTitle = AcademicTitle::find($id);

            $academicTitle->academic_title = $request->academic_title;

            if($academicTitle->save()){
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

    public function destroy($id)
    {
        $academicTitle = AcademicTitle::find($id);
        $academicTitle->delete();

        return redirect('academicTitlePanel');
    }
}
