<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Http\Request;
use Mail;
use Sentinel;
use Validator;
use Activation;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Illuminate\Database\QueryException;
use ErrorException;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'log_id' => 'bail|required',
                'password' => 'bail|required',
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails' => true,
                    'errors' => $validator->getMessageBag()->toArray()
                ],400);
            }

            if(Sentinel::authenticate(['email'=>$request->log_id, 'password'=>$request->password])){
                return response()->json([
                    'success'=>true,
                    'url' => 'http://'.env('APP_URL'),
                ],200);
            }elseif(Sentinel::authenticate(['log_id'=>$request->log_id, 'password'=>$request->password])){
                return response()->json([
                    'success'=>true,
                    'url' => 'http://'.env('APP_URL'),
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim në të dhëna',
                    'msg'=> 'Ju lutem shtypni të dhënat e sakta!'
                ],400);
            }
        }
        catch(ThrottlingException $e){
            $delay = $e->getDelay();

            return response()->json([
                'fails'=>true,
                'title'=> 'Shumë tentime për qasje',
                'msg'=>"Ju është ndaluar qasja në faqe për $delay sekonda"
            ],400);
        }
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
            $destination_folder = 'Uploads/';

            $validator = Validator::make($request->all(),[
                'first_name' => 'bail|required|alpha|max:190',
                'last_name' => 'bail|required|alpha|max:190',
                'email' => 'bail|required|email|max:190',
                'password'=>'bail|required|confirmed|max:190',
                'personal_number'=>'bail|required|numeric',
                'cpa_id' => 'bail|required|numeric',
                'acedemical_title_id' => 'bail|required|numeric',
                'photo' => 'bail|required|image|mimes:jpeg,png|max:1000000',
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë validimit!',
                    'msg'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $data = $request->all();
            if($request->hasFile('photo')){

                $file = $request->file('photo');

                $filename = str_random(8).'-'.$file->getClientOriginalName();

                $file->move($destination_folder,$filename);

                $data['photo']=$destination_folder.$filename;
            }

            $log_ids = User::select('log_id')->get()->toArray();

            do{
                $log_id = rand(1000,99999);
            }while(in_array($log_id,$log_ids));

            $data['log_id'] = $log_id;
            
            if($user = Sentinel::register($data)){
                if($activation = Activation::create($user)){
                    if($role = Sentinel::findRoleBySlug('user')){
                        $role->users()->attach($user);

                        $this->sendMail($user, $activation->code);

                        return response()->json([
                            'success'=>true,
                            'title'=>'Aktivizimi',
                            'msg'=>'Për të aktivizuar llogarinë tuaj kontrollo postën tuaj elektronike!'
                        ],200);
                    }else{
                        return response()->json([
                            'fails'=>true,
                            'title'=>'Gabim internal',
                            'msg'=>'Ju lutemi kontaktoni mbështetësit e faqes!'
                        ],400);
                    }
                }
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë regjistrimit',
                    'msg'=>'Ju lutemi shikoni për parregullsi në të dhëna!'
                ],400);
            }
        }catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në server!',
                'msg'=>$e->getMessage()
            ],500);
        }
        catch(ErrorException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim në server!',
                'msg'=>$e->getMessage()
            ],500);
        }

    }

    public function sendMail($user,$code){
        Mail::send('emails.activation',[
            'user'=>$user,
            'code'=>$code
        ], function($message) use($user){
            $message->to($user->email);
            $message->subject("Përshëndetje $user->first_name $user->last_name, aktivizo llogarinë tuaj!");
        });
    }

    public function logout(){
        if(Sentinel::check()){
            Sentinel::logout();
            return redirect('login');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
