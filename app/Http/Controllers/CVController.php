<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cv;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Transformers\CVTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class CVController extends Controller
{
    //Display one requested cv
    public function find($id)
    {
        $cv = cv::where('user_id', $id)->get();

        if(count($cv)){
            return fractal()
            ->collection($cv)
            ->parseIncludes([])
            ->transformWith(new CVTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'cv is not available or deleted!']
                ], 404);
        }
    }


    public function storefile(Request $request)
    {
        $this->validate(request(), [
            'user_id' => 'required',
            'cv_file' => 'required | mimes:pdf',
        ]); 

        $filename = $request->user_id . '_cv.pdf';
        
        $path = $request->file('cv_file')->storeAs(
        'cv', $filename
        );


        return $filename;
    }

    public function deletefile($id){
        $filename = 'cv/' . $id . '_cv.pdf';
        Storage::delete($filename);
    }

    public function add(Request $request)
    {
        $this->validate(request(), [
            'user_id' => 'required',
            'professional_title' => 'required',
            'address' => 'required',
            'skills' => 'required',
            'wish' => 'required',
            'cv_link' => 'required',
            'status' => 'required',
        ]);

        $cv = new cv;
        $cv->user_id = $request->user_id;
        $cv->professional_title = $request->professional_title;
        $cv->address = $request->address;
        $cv->skills = $request->skills;
        $cv->wish = $request->wish;
        $cv->cv_link = $request->cv_link;
        $cv->status = $request->status;
        $cv->created_at = Carbon::now();
        $cv->updated_at = Carbon::now();

     try{
            $cv->save();
        } catch (\PDOException $e){
            return 'data:' . json_encode(array(array('message'=>'Something worng! Please try again...')));
        }

        return 'data:' . json_encode(array(array('message'=>'CV successfully added.')));
    }

    public function delete($id){
        try{
            $deleteddata = cv::where('user_id', $id)->delete();
            $this->deletefile($id);
        } catch (\PDOException $e){
            return 'data:' . json_encode(array(array('message'=>'Something worng! Please try again...')));
        }
        return 'data:' . json_encode(array(array('message'=>'CV successfully deleted.')));

    }

    publc function update($id){
        
    }


}
