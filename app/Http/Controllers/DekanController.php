<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Sentinel;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Status;
use App\Models\Cpa;
use DB;

class DekanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
                'academic_title_id' => 'bail|required|numeric',
                'role_id' => 'bail|required|numeric',
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
            unset($data['role_id']);
            $data['status_id'] = Status::select('id')->where('name','Rregullt')->orWhere('name','rregullt')->get()->toArray()[0]['id'];
            if($data['status_id'] == null){
                if($validation->fails()){
                    return response()->json([
                        'fails'=>true,
                        'title'=>'Statusi i profesorit',
                        'msg'=>'Per titullin akademik te caktuar nuk ekziston statusi i Rregullt. Fillimisht shtoni statusin i Rregullt pastaj shtoni dekanët!'
                    ],500);
                }
            }
            if(CPA::select('id')->where('cpa','Dekan')->orWhere('cpa','dekan')->exists()){
                $data['cpa_id'] = CPA::select('id')->where('cpa','Dekan')->orWhere('cpa','dekan')->get()->toArray()[0]['id'];
                if($user = Sentinel::registerAndActivate($data)){
                    if($role = Sentinel::findRoleById($request->role_id)){
                        $role->users()->attach($user);

                        return response()->json([
                            'success'=>true,
                            'title'=>'Sukses',
                            'msg'=>'Të dhënat u shtuan me sukses!'
                        ],200);
                    }else{
                        return response()->json([
                            'fails'=>true,
                            'title'=>'Gabim me rolin',
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
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë regjistrimit',
                    'msg'=>'Ju lutemi regjistroni dekan tek thirrjet akademike!'
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
        catch(\ErrorException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
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
        DB::enableQueryLog();
        try{
            $data = User::with('academic_title','roles')->with(['status'=>function($query) use($request){
                $query->orWhere('name','like','%'.$request->search.'%');
            }])->where(function ($query) use ($request){
                $query->orWhere('first_name', 'like','%' .$request->search.'%');
                $query->orWhere('last_name','like','%'.$request->search.'%');
                $query->orWhere('email','like','%' .$request->search.'%');
            })->whereHas('cpa',function($query) use($request){
                $query->where('cpas.cpa','Dekan');
            })->paginate(10);
    //    dd(DB::getQueryLog());
            return view('Menaxho.Dekanet.panel')->with('data',$data);
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
    public function edit(Request $request,$id,$photo)
    {
        DB::enableQueryLog();
        try{
            $destinationFolder = 'Uploads/';
            $validation = Validator::make($request->all(),[
                'first_name' => 'bail|required|alpha|max:190',
                'last_name' => 'bail|required|alpha|max:190',
                'email' => 'bail|required|email|max:190',
                'personal_number'=>'bail|required|string',
                'academic_title_id' => 'bail|required|numeric',
                'role_id' => 'bail|required|numeric'
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
            }else{
                $data['photo'] = $destinationFolder.$photo;
            }

            unset($data['role_id']);
            $data['status_id'] = Status::select('id')->where('name','Rregullt')->orWhere('name','rregullt')->get()->toArray()[0]['id'];
            $data['cpa_id'] = CPA::select('id')->where('cpa','Dekan')->get()->toArray()[0]['id'];

            if($dekan = User::find($id)){
                $dekan->first_name = $data['first_name'];
                $dekan->last_name = $data['last_name'];
                $dekan->email = $data['email'];
                $dekan->personal_number = $data['personal_number'];
                $dekan->cpa_id = $data['cpa_id'];
                $dekan->academic_title_id = $data['academic_title_id'];
                $dekan->status_id = $data['status_id'];
                $dekan->photo = $data['photo'];
                if($dekan->save()){
                    if(RoleUser::where('user_id',$dekan->id)->update(['role_id'=>$request->role_id])){
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
        $dekan = Sentinel::findById($id);
        $dekan->delete();

        return redirect('dekanet');
    }
}
