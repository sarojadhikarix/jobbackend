<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Transformers\UserBriefTransformer;

class UserController extends Controller
{


    public function find($id)
    {
        $user = User::find($id);

        if(count($user)){
            return fractal()
            ->item($user)
            ->parseIncludes([])
            ->transformWith(new UserBriefTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'User is not available or deleted!']
                ], 404);
        }
    }
}