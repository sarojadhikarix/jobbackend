<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\forgotpasswordmessage;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        if ($request->wantsJson()) {
            $user = User::where('email', $request->input('email'))->first();
            if (!$user) {
                $returnData = array(
                    'message' => trans('passwords.user'),
                    'status' => false
                );
                return response()->json($returnData, 422); 
            }
            $token = $this->broker()->createToken($user);
            try{
                \Mail::to($request->email)->send(new forgotpasswordmessage($token));
            }catch(\PDOException $e){
                $returnData = array(
                    'message' => 'Mail was not sent. Check your email or write us...',
                    'status' => false
                );
                return response()->json($returnData, 422); 
            }
            $returnData = array(
                'status' => true
            );
            return response()->json($returnData, 200);
        }
        
    }
}
