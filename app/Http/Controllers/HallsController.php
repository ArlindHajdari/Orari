<?php

namespace App\Http\Controllers;

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
            
            $validation = Validator::make($request->all(),[
                'hall' => 'bail|required|alpha_num|max:190',
                'capacity' => 'bail|required|numeric|max:190',
                'halltype_id' => 'bail|required|numeric|max:190',
          
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }
            $hall = new Hall();
            $hall->hall=$request->hall;
            $hall->capacity=$request->capacity;
            $hall->halltype_id=$request->halltype_id;
            if($hall->save())
            {
                    return response()->json([
                        'success'=>true,
                        'title'=>'Sukses',
                        'msg'=>'Të dhënat u shtuan me sukses!'
                    ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë regjistrimit',
                    'msg'=>'Ju lutemi shikoni për parregullsi në të dhëna!'
                ],500);
            }
        }
    
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin'
            ],500);
        }
    }

    
    public function show(Request $request)
    {
        DB::enableQueryLog();
        try{
            $data = Hall::paginate(10);

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
            
            $validation = Validator::make($request->all(),[
                'hall' => 'bail|required|alpha_num|max:190',
                'capacity' => 'bail|required|numeric|max:190',
                'halltype_id' => 'bail|required|numeric|max:190',
                
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            $data = $request->all();
           
            if($hall = Hall::find($id)){
                $hall->hall = $data['hall'];
                $hall->capacity = $data['capacity'];
                $hall->halltype_id = $data['halltype_id'];
                
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
                            'msg'=>'Të dhënat nuk u ndryshuan!'
                        ],500);
                    }
            }
                else{
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
