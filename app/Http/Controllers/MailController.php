<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail;
use Illuminate\Support\Str;
use App\Mail\message;
use Carbon\Carbon;

class MailController extends Controller
{
	    public function send(Request $data){
        $name = $data->name;
        $toemail = $data->toemail;
        $byemail = $data->byemail;
        $message = $data->message;

        \Mail::to($toemail)->send(new message($data));
        if($byemail != '' && $toemail != '' && $message != '' && $name != ''){
        	

                    $mail = new Mail;
                    $mail->name = $name;
                    $mail->toemail = $toemail;
                    $mail->byemail = $byemail;
                    $mail->message = $message;
                    $mail->created_at = Carbon::now();
                    $mail->updated_at = Carbon::now();
                    $mail->status = 1;
                    $mail->save();

                return response()->json([
                    'data' => [
                        'success' => 'Message sent.']
                    ]);



        } else{
            return response()->json([
                'data' => [
                    'error' => 'Message not sent.']
                ]);
        }

        
    }
}