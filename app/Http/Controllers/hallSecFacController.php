<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use Validator;
use Sentinel;
use Illuminate\Database\QueryException;

class hallSecFacController extends Controller
{

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
        try
        {
            $data = $request->all();

//            $validation = Validator::make($data,[
//                'id' => 'bail|required',
//                'sec_faculty_id' => 'bail|required'
//            ]);
//
//            if($validation->fails())
//            {
//                return response()->json([
//                    'fails'=>true,
//                    'errors'=>$validation->getMessageBag()->toArray()
//                ],400);
//            }

            if($hall = Hall::find($request->id)){

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

        }catch (QueryException $e)
        {
            return response()->json([
                'fails'=>true,
                'title'=>'Internal server error',
                'msg'=>$e->getMessage()
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
        $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

        try{
            $data = Hall::select('halls.id','halls.hall','faculties.faculty','fa.faculty as sec_faculty','faculties.id as faculty_id','fa.id as sec_faculty_id')->join('faculties','halls.faculty_id','faculties.id')->join('faculties as fa','halls.sec_faculty_id','fa.id')->where('halls.hall','like','%'.$request->search.'%')->where(function($query) use($request,$faculty)
            {
                $query->where('faculties.faculty',$faculty);
            })->paginate(10);

            return view('Menaxho.Sallat.secPanel')->with('data',$data);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request,$id)
    {
        try
        {
            $data = $request->all();

            $validation = Validator::make($data,[
                'sec_faculty_id' => 'bail|required'
            ]);

            if($validation->fails())
            {
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            if($hall = Hall::find($id)){

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
                        'msg'=>'Të dhënat nuk u ndryshuan!'
                    ],500);
                }
            } else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim internal',
                    'msg'=>'Ju lutemi kontaktoni mbështetësit e faqes!'
                ],500);
            }

        }catch (QueryException $e)
        {
            return response()->json([
                'fails'=>true,
                'title'=>'Internal server error',
                'msg'=>$e->getMessage()
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
        if($hall = Hall::where('id',$id)->update(['sec_faculty_id'=>null])){
            return redirect('secFaculty');
        }else{
            return redirect('/');
        }
    }
}
