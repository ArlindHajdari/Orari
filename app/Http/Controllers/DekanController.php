<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Sentinel;
use App\Models\User;
use DB;

class DekanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Menaxho.Dekanet.panel');
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
        try{
            $destinationFolder = 'Uploads/';

            $validation = Validator::make($request->all(),[
                'first_name' => 'bail|required|alpha|max:190',
                'last_name' => 'bail|required|alpha|max:190',
                'email' => 'bail|required|email|max:190',
                'password'=>'bail|required|max:190',
                'personal_number'=>'bail|required|numeric',
                'cpa_id' => 'bail|required|numeric',
                'academic_title_id' => 'bail|required|numeric',
                'role' => 'bail|required|numeric',
                'photo' => 'bail|required|image|mimes:jpeg,png|max:1000000'
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            $data = $request->all();
            if($request->hasFile('photo')){
                $file = $request->file('photo');

                $filename = str_random(8).'-'.$file->getClientOriginalName();

                $file->move($destinationFolder,$filename);

                $data['photo']=$destinationFolder.$filename;
            }

            $log_ids = User::select('log_id')->get()->toArray();

            do{
                $log_id = rand(1000,99999);
            }while(in_array($log_id,$log_ids));

            $data['log_id'] = $log_id;

            if($user = Sentinel::registerAndActivate($data)){
                if($role = Sentinel::findRoleById($data['role'])){
                    $role->users()->attach($user);

                    return response()->json([
                        'success'=>true,
                        'title'=>'Sukses',
                        'msg'=>'Të dhënat u shtuan me sukses!'
                    ],200);
                }else{
                    return response()->json([
                        'fails'=>true,
                        'title'=>'Gabim internal',
                        'msg'=>'Ju lutemi kontaktoni mbështetësit e faqes!'
                    ],500);
                }

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'search' => 'bail|required|string'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $data = User::select('users.id',DB::raw("concat(academic_titles.academic_title,'.',users.first_name,' ',users.last_name) as full_name"),'cpas.cpa','users.email','users.personal_number','users.log_id','users.photo')
                ->join('cpas','users.cpa_id','cpas.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->where
            ('first_name',
                'like','%'.$request->search.'%')
                ->orWhere
            ('last_name',
                'like','%'
                .$request->search.'%')->orWhere('email','like','%'.$request->search.'%')->orWhere('personal_number','like','%'.$request->search.'%')->orWhere('log_id','like','%'.$request->search.'%')->where('cpa','Dekan')->paginate
                (10);
            return view('Menaxho.Dekanet.panel',['data'=>$data]);
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
        //
    }
}
