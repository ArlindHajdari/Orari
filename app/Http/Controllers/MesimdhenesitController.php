<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Validator;
use Sentinel;
use App\Models\User;
use App\Models\RoleUser;
use Mail;
use DB;
class MesimdhenesitController extends Controller
{

    private function sendTeachersData($user)
    {
          Mail::send('emails.teacher_data',[
              'user'=>$user
          ],function($message) use($user){
            $message->to($user->email);
            $message->subject("Të dhënat për përdorimin e aplikacionit XALFA");
          });
    }

    public function store(Request $request)
    {
        try{
            $destinationFolder = 'Uploads/';

            $validation = Validator::make($request->all(),[
                'first_name' => 'bail|required|alpha|max:190',
                'last_name' => 'bail|required|alpha|max:190',
                'email' => 'bail|required|email|max:190|unique:users,email',
                'password'=>'bail|required|max:190',
                'personal_number'=>'bail|required|numeric|unique:users,personal_number',
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

            $data = $request->all();

            $file = $request->file('photo');

            $filename = str_random(8).'-'.$file->getClientOriginalName();

            $file->move($destinationFolder,$filename);

            $data['photo']=$destinationFolder.$filename;

            $log_ids = User::select('log_id')->get()->toArray();

            do{
                $log_id = rand(1000,9999);
            }while(in_array($log_id,$log_ids));

            $data['log_id'] = $log_id;

            if($register = Sentinel::registerAndActivate($data)){
                if($role = Sentinel::findRoleBySlug('user')){
                    $role->users()->attach($register);

                    $this->sendTeachersData($register);

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
        DB::enableQueryLog();
        try{
            $data = User::select('users.id','cpas.cpa','users.first_name','users.last_name','users.cpa_id','users.academic_title_id',DB::raw("concat(academic_titles.academic_title,users.first_name,' ',users.last_name) as full_name"),'users.email','users.personal_number','users.log_id','users.photo','status.name','users.status_id')->join('academic_titles','users.academic_title_id', 'academic_titles.id')->join('cpas','users.cpa_id','cpas.id')->join('role_users','users.id','role_users.user_id')->join('status','users.status_id','status.id')->join('roles','role_users.role_id','roles.id')->where('cpas.cpa','<>','Dekan')->where('roles.slug','<>', 'admin')->where(function($query) use ($request){
                $query->orWhere('users.last_name','like','%'.$request->search.'%');
                $query->orWhere('users.email','like','%'.$request->search.'%');
                $query->orWhere('users.personal_number','like','%'.$request->search.'%');
                $query->orWhere('users.log_id','like','%'.$request->search.'%');
                $query->orWhere('users.first_name','like','%'.$request->search.'%');
                $query->orWhere('cpas.cpa','like','%'.$request->search.'%');
                $query->orWhere('status.name','like','%'.$request->search.'%');
            })->paginate(15);

            return view('Menaxho.Mesimdhenesi.panel')->with('data',$data);
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
                    if(RoleUser::where('user_id',$dekan->id)->update(['role_id'=>Sentinel::findRoleBySlug('user')['id']])){
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
