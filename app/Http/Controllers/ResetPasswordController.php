<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Mail;
use App\Models\User;
use Reminder;
use View;

class ResetPasswordController extends Controller
{
    public function index(){
        if(View::exists('recoverpassword'))
            return view('recoverpassword');
        else{
            return view('errors.404');
        }
    }

    public function getResetFields($email, $code){
        $user = User::byEorU($email);
        //$sentinelUser = Sentinel::findById($user->id);

        if($reminder = Reminder::exists($user)){
            if($reminder->code == $code)
                return view('recoverpassword1');
            else
                return redirect('/')->with('error','Wrong URL link!');
        }else
            return redirect('/')->with('error','We haven\'t sent any email to this user!');
    }

    public function resetPassword(Request $request){
        $user = User::byEorU($request->email);

        //$sentinelUser = Sentinel::findById($user->id);

        $reminder = Reminder::exists($user) ?: Reminder::create($user);

        $this->sendMail($user, $reminder->code);

        return redirect('/')->with('mailsent','Please check your e-mail for further steps!');
    }

    public function postRecover(Request $request, $email, $code){
        $this->validate($request,[
            'password' => 'confirmed|required|min:6|max:150',
            'password_confirmation' => 'required|min:6|max:150'
        ]);

        $user = User::byEorU($email);
        //$sentinelUser = Sentinel::findById($user->id);

        if($reminder = Reminder::exists($user)){
            if($code == $reminder->code){
                Reminder::complete($user, $code, $request->password);
                Sentinel::authenticate(['email'=>$user->email,'password'=>$request->password]);
                return redirect('recoverComplete');
            }else{
                return redirect('/')->with('errors', 'Code doesn\'t match!');
            }
        }else{
            return redirect('/')->with('errors', 'The reset password code can\'t be found!');
        }
    }

    public function sendMail($user, $code){
        Mail::send('emails.forgot_password',[
            'user'=>$user,
            'code'=>$code
        ],function($message) use($user){
            $message->to($user->email);

            $message->subject("HOTEL | Recover password");
        });
    }
}
