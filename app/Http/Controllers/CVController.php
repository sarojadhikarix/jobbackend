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
        $cv = cv::where('user_id', $id)->first();

        if(count($cv)){
            return fractal()
            ->item($cv)
            ->parseIncludes([])
            ->transformWith(new CVTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'not_found']
                ], 200);
        }
    }


    public function storefile(Request $request)
    {
        $this->validate(request(), [
            'user_id' => 'required',
            'cv_file' => 'required | mimes:pdf',
        ]); 

        $filename = $request->user_id . '_cv.pdf';
        

        try{
            $path = $request->file('cv_file')->storeAs(
            'public/storage/cv', $filename
            );
        } catch (\PDOException $e){
            $returnData = array(
                'error' => 'Something worng! Error while uploading file.'
            );

            return response()->json($returnData, 200);
        }

            $returnData = array(
                'success' => 'CV file successfully uploaded.',
                'filename' => $filename
            );

            return response()->json($returnData, 200);
    }

    public function deletefile($id){
        $filename = 'public/storage/cv/' . $id . '_cv.pdf';
        
        try{
            Storage::delete($filename);
        } catch (\PDOException $e){
            return 'data:' . json_encode(array(array('message'=>'Something worng! Error while deleting file.')));
        }
        return 'data:' . json_encode(array(array('message'=>'CV file successfully deleted.')));
    }

    public function add(Request $request)
    {
    // If policy return true => authorized
    //This authorize only user who own the post can edit it.
    //$this->authorize('add', $request);

        $this->validate(request(), [
            'user_id' => 'required | unique:CV,user_id,' . $request->user_id,
            'professional_title' => 'required',
            'address' => 'required',
            'skills' => 'required',
            'wish' => 'required | max:1300',
            'cv_link' => 'required',
        ]);

        $cv = new cv;
        $cv->user_id = $request->user_id;
        $cv->professional_title = $request->professional_title;
        $cv->address = $request->address;
        $cv->skills = $request->skills;
        $cv->wish = $request->wish;
        $cv->cv_link = $request->cv_link;
        $cv->status = 1;
        $cv->created_at = Carbon::now();
        $cv->updated_at = Carbon::now();

     try{
            $cv->save();
        } catch (\PDOException $e){

            $returnData = array(
                'message' => 'Something worng! Please try again...'
            );

            return response()->json($returnData, 200);
        }

            $returnData = array(
                'message' => 'CV successfully added.'
            );

            return response()->json($returnData, 200);

    }

    public function delete($id){
        // If policy return true => authorized
        //This authorize only user who own the post can edit it.
    //$this->authorize('delete', $id);
        try{
            $deleteddata = cv::where('user_id', $id)->delete();
            $this->deletefile($id);
        } catch (\PDOException $e){
            return 'data:' . json_encode(array('message'=>'Something worng! Please try again...'));
        }
        return 'data:' . json_encode(array('message'=>'CV successfully deleted.'));

    }

 public function update(Request $request)
 {
        // If policy return true => authorized
        //This authorize only user who own the post can edit it.
    //$this->authorize('update', $request);
    try{
    $edited_character = cv::where('user_id', $request->user_id)->update([
        'professional_title' => $request->professional_title,
        'address' => $request->address,
        'skills' => $request->skills,
        'wish' => $request->wish,
        'cv_link' => $request->cv_link,
        'status' => $request->status,
        'updated_at' => Carbon::now(),
        //others property
    ]);
    }catch (\PDOException $e){
            return 'data:' . json_encode(array(array('message'=>'Something worng while updating! Please try again...')));
    }
    return 'data:' . json_encode(array(array('message'=>'CV successfully updated.')));

}

    public function search(Request $request){

        $keyword = $request->keyword;
        $location = $request->location;
        if($keyword != '' || $location != ''){
            $cv = cv::Search($keyword, $location)->get();
                if(count($cv) > 0){
                    return fractal()
                    ->collection($cv)
                    ->parseIncludes(['user'])
                    ->transformWith(new CVTransformer)
                    ->toArray();
                 }else{
                return response()->json([
                    'error' => [
                        'status' => 'Search not found!']
                    ], 404);
                 }
        } else{
            return response()->json([
                'error' => [
                    'status' => 'Search not found!']
                ], 404);
        }
    }


}
