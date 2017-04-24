<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Mail;
use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;
use Reminder;
use View;

class ResetPasswordController extends Controller
{
    public function index(){
        if(View::exists('password_recovery.recoverpassword'))
            return view('password_recovery.recoverpassword');
        else{
            return view('errors.404');
        }
    }

    public function recover(){
        if(View::exists('password_recovery.recoverpassword1'))
            return view('password_recovery.recoverpassword1');
        else{
            return view('errors.404');
        }
    }

    public function resetPassword(Request $request){
        \DB::enableQueryLog();
        $validator = Validator::make($request->all(),[
            'email'=>[
                'required',
            Rule::exists('users')->where(function($query) use ($request){
              $query->where('email',$request->email);
              $query->orWhere('log_id',$request->email);
            })]
        ]);

        if($validator->fails()){
            return response()->json([
                'email'=>'Të dhënat e shtypura nuk ekzistojnë'
            ],400);
        }

        $user = User::where('email',$request->email)->orWhere('log_id',$request->email)->get();

        if($sentinel_user = Sentinel::findUserById($user->id)){
            if($reminder = Reminder::exists($sentinel_user) ?: Reminder::create($sentinel_user)){
                $this->sendMail($sentinel_user, $reminder->code);

                return response()->json([
                    'title'=>'Sukses',
                    'msg'=>'Ju lutem kontrolloni postën tuaj elektronike për të vazhduar!',
                    'url'=>url('login')
                ],200);
            }else{
                return response()->json([
                    'email'=>'Të dhënat e shtypura nuk ekzistojnë'
                ],400);
            }
        }else{
            return response()->json([
                'email'=>'Të dhënat e shtypura nuk ekzistojnë'
            ],400);
        }
    }

    public function postRecover(Request $request, $email, $code){

        $validator = Validator::make($request->all(),[
            'password' => 'confirmed|required|min:6|max:150',
            'password_confirmation' => 'required|min:6|max:150'
        ]);

        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->getMessageBag()->toArray()
            ],400);
        }

        $user = User::where('email',$request->email)->orWhere('log_id',$request->email)->get();

        $sentinel_user = Sentinel::findUserById($user->id);

        if($reminder = Reminder::exists($sentinel_user)){
            if($code == $reminder->code){
                Reminder::complete($sentinel_user, $code, $request->password);
                Sentinel::authenticate(['email'=>$user->email,'password'=>$request->password]);
                return response()->json([
                    'title'=>'Sukses',
                    'msg'=>'Fjalëkalimi u ndërrua me sukses!',
                    'url'=>url('/')
                ],200);
            }else{
                return response()->json([
                    'title'=>'Gabim',
                    'msg'=>'Kodet nuk përputhen, ju lutem kontaktoni mirëmhajtësit e faqes!'
                ],500);
            }
        }else{
            return response()->json([
                'title'=>'Gabim',
                'msg'=>'Kodi për ndryshimin e fjalekalimit nuk ekziston!'
            ],500);
        }
    }

    public function sendMail($user, $code){
        Mail::send('emails.forgot_password',[
            'user'=>$user,
            'code'=>$code
        ],function($message) use($user){
            $message->to($user->email);

            $message->subject("XALFA | Ndrysho fjalëkalimin");
        });
    }
}
