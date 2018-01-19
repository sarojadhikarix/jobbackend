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

        
        if($byemail != '' && $toemail != '' && $message != '' && $name != ''){
                    
            $mail = new Mail;
            $mail->name = $name;
            $mail->toemail = $toemail;
            $mail->byemail = $byemail;
            $mail->message = $message;
            $mail->created_at = Carbon::now();
            $mail->updated_at = Carbon::now();
            $mail->status = 1;
                    
            try{
                $mail->save();      
                \Mail::to($toemail)->send(new message($data));
            }catch(\PDOException $e){
                $returnData = array(
                    'error' => 'Mail send failed.'
                );
                    return response()->json($returnData);
            }



                return response()->json([
                    'data' => [
                        'success' => $name . $toemail . $byemail . $message]
                    ]);



        } else{
            return response()->json([
                'data' => [
                    'error' => 'Please fill all the fields.']
                ]);
        }

        
    }
}