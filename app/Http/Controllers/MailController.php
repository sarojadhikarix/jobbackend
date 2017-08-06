<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Mail\message;
use Carbon\Carbon;

class MailController extends Controller
{
	    public function send(Request $data){
        $name = $data->name;
        $email = $data->email;
        $message = $data->message;
        if($email != '' && $message != '' && $name != ''){
        	\Mail::to('creative@hopnep.com')->send(new message($data));
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