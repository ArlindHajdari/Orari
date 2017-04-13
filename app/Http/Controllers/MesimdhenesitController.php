<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Sentinel;
use App\Models\User;
use App\Models\AcademicTitle;
use App\Models\RoleUser;
use App\Models\cpa;
use DB;
class MesimdhenesitController extends Controller
{
    
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
                'photo' => 'bail|required|image|mimes:jpeg,png|max:1000000'
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

           
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
           // $data['puna_ids']=$puna_ids;
            unset($data['role_id']);
             $data = new User();
            $data->first_name=$request->first_name;
            $data->last_name=$request->last_name;
            $data->log_id=$log_id;
            $data->password=$request->password;
            $data->personal_number=$request->personal_number;
            $data->cpa_id=$request->cpa_id;
            $data->academic_title_id=$request->academic_title_id;
            $data->email=$request->email;
            
            if($user = Sentinel::registerAndActivate($data)){
                if($role = Sentinel::findRoleBySlug('user')){
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
         $cpa_id=cpa::where('cpa','!=','Dekan')->pluck('cpa','id')->toArray();
         $academicalTitles=AcademicTitle::pluck('academic_title','id');
        DB::enableQueryLog();
        try{
            $data = User::select('users.id','users.first_name','users.last_name','users.cpa_id','users.academic_title_id',DB::raw("concat(academic_titles.academic_title,users.first_name,' ',users.last_name) as full_name"),'users.email','users.personal_number','users.log_id','users.photo', 'role_users.role_id')->join('role_users','role_users.user_id','users.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->join('cpas','users.cpa_id','cpas.id')->where('cpas.cpa','Dekan')->where(function($query) use ($request){
                $query->orWhere('users.last_name','like','%'.$request->search.'%');
                $query->orWhere('users.email','like','%'.$request->search.'%');
                $query->orWhere('users.personal_number','like','%'.$request->search.'%');
                $query->orWhere('users.log_id','like','%'.$request->search.'%');
                $query->orWhere('users.first_name','like','%'.$request->search.'%');
            })->paginate(10);

            return view('Menaxho.Mesimdhenesi.panel')->with('data',$data)
                                                ->with('cpa_id',$cpa_id)
                                                ->with('academicalTitles',$academicalTitles);;
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
                'cpa_id' => 'bail|required|numeric|',
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
            if($dekan = User::find($id)){
                $dekan->first_name = $data['first_name'];
                $dekan->last_name = $data['last_name'];
                $dekan->email = $data['email'];
                $dekan->personal_number = $data['personal_number'];
                $dekan->cpa_id = $data['cpa_id'];
                $dekan->academic_title_id = $data['academic_title_id'];
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

    public function destroy($id)
    {
        $mesimdhenesi = User::find($id);
        $mesimdhenesi->delete();

        return redirect('mesimdhenesit');
    }
}