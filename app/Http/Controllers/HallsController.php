<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Halltype;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Sentinel;
use App\Models\Hall;
use DB;

class HallsController extends Controller
{
    public function store(Request $request)
    {
        try{
            $data = $request->all();

            $validate = Validator::make($data, [
                'hall' => 'bail|required|string|max:10|unique:halls',
                'capacity' => 'bail|required|string|max:10',
                'halltype_id' => 'bail|required|int|min:1',
                'faculty_id' => 'bail|required|int|min:1',
                'sec_faculty_id' => 'bail|nullable|int|min:1'
            ]);

            if($validate->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validate->getMessageBag()->toArray()
                ],400);
            }

            $halls = new Hall;
            $halls->hall = $request->hall;
            $halls->capacity = $request->capacity;
            $halls->halltype_id = $request->halltype_id;
            $halls->faculty_id = $request->faculty_id;
            if(isset($request->sec_faculty_id)){
                $halls->sec_faculty_id = $request->sec_faculty_id;
            }
            
            if($halls->save()){

                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg'=>'Të dhënat u ruajtën me sukses!'
                ],200);
            }else{
                return response()->json([
                    'fails'=> true,
                    'title'=> 'Gabim gjatë futjes së të dhënave',
                    'msg'=>'Të dhënat nuk mundën të futen në databaze, ju lutem kontaktoni mirëmbajtësit e faqes!'
                ],500);
                }
            }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Internal server error',
                'msg'=>$e->getMessage()
            ],500);
        }
    }

    public function show(Request $request)
    {
        try{
            $data = Hall::select('halls.id','halls.hall','halls.capacity','halltypes.hallType','faculties.faculty','fa.faculty as sec_faculty','halltypes.id as halltype_id','faculties.id as faculty_id','fa.id as sec_faculty_id')->leftJoin('halltypes', 'halls.halltype_id','halltypes.id')->leftJoin('faculties','halls.faculty_id','faculties.id')->leftJoin('faculties as fa','halls.sec_faculty_id','fa.id')->where('halls.hall','like','%'.$request->search.'%')->orWhere('halls.capacity','like','%'.$request->search.'%')->orWhere('halltypes.hallType','like','%'.$request->search.'%')->paginate(10);

            return view('Menaxho.Sallat.panel')->with('data',$data);
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }
    }

 
    public function edit(Request $request,$id)
    {
        DB::enableQueryLog();
        try{
            $data = $request->all();

            $validation = Validator::make($data,[
                'hall' => 'bail|required|alpha_num|max:190',
                'capacity' => 'bail|required|numeric|max:190',
                'halltype_id' => 'bail|required|numeric|max:190',
                'faculty_id' => 'bail|required|numeric|exists:faculties,id',
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            if($hall = Hall::find($id)){
                $hall->hall = $data['hall'];
                $hall->capacity = $data['capacity'];
                $hall->halltype_id = $data['halltype_id'];
                $hall->faculty_id = $data['faculty_id'];
                $hall->sec_faculty_id = $data['sec_faculty_id'];

                if($hall->save()){
                        return response()->json([
                            'success'=>true,
                            'title'=>'Sukses',
                            'msg'=>'Të dhënat u ndryshuan me sukses!'
                        ],200);
                    }else{
                        return response()->json([
                            'fails'=>true,
                            'title'=>'Gabim gjatë ndryshimit',
                            'msg'=>'Të dhënat nuk u ndryshuan me sukses!'
                        ],500);
                    }
            } else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim internal',
                    'msg'=>'Ju lutemi kontaktoni mbështetësit e faqes!'
                ],500);
            }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hall = Hall::find($id);
        $hall->delete();

        return redirect('sallat');
    }
}
