<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Jobs;

use Illuminate\Support\Str;
use App\Transformers\JobsTransformer;
use Carbon\Carbon;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $jobs = Jobs::whereDate('finish', '>=', Carbon::today()->toDateString())->orderBy('finish', 'asc')->get();
            return fractal()
            ->collection($jobs)
            ->parseIncludes(['category'])
            ->transformWith(new JobsTransformer)
            ->toArray();
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
        $job = new jobs;
        $job->title = $request->title;
        $job->description = $request->description;
        $job->category_id = $request->category;
        $job->company_name = $request->company_name;
        $job->company_website = $request->company_website;
        $job->company_email = $request->company_email;
        $job->company_phone = $request->company_phone;
        $job->company_logo = $request->company_logo;
        $job->company_facebook = $request->company_facebook;
        $job->company_video = $request->company_video;
        $job->keywords = $request->keywords;
        $job->type = $request->type;
        $job->requirements = $request->requirements;
        $job->user_id = $request->user_id;
        $job->finish = $request->finish;
        $job->city = $request->city;
        $job->district = $request->district;
        $job->zone = $request->zone;
        $job->country = $request->country;
        $job->status = 0;

        if(empty($request->created_at))
            $job->created_at = Carbon::now();
        else
         $job->created_at = $request->created_at;

     $job->updated_at = Carbon::now();

     try{
            $job->save();
        } catch (\PDOException $e){
            return 'data:' . json_encode(array(array('message'=>'Something worng! Please try again...')));
        }

        return 'data:' . json_encode(array(array('message'=>'Job successfully added.')));
     // return fractal()
     // ->item($job)
     // ->transformWith(new JobsTransformer)
     // ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        $job = Jobs::find($id);

        if(count($job)){
            return fractal()
            ->item($job)
            ->parseIncludes(['category'])
            ->transformWith(new JobsTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'Job is not available or deleted!']
                ], 404);
        }
    }

    public function sort($name){
        if($name == 'finish'){
        $jobs = Jobs::whereDate('finish', '>=', Carbon::today()->toDateString())->where('status', '=', 1)->orderBy('finish', 'asc')->get();
    }else{
        $jobs = Jobs::whereDate('finish', '>=', Carbon::today()->toDateString())->where('status', '=', 1)->orderBy($name, 'desc')->get();
    }

        if(count($jobs)){
            return fractal()
            ->collection($jobs)
            ->parseIncludes(['users'])
            ->transformWith(new JobsTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'Something Wrong!']
                ], 404);
        }
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

    public function search(Request $request){

        $keyword = $request->keyword;
        $location = $request->location;
        if($keyword != '' || $location != ''){
            $jobs = Jobs::Search($keyword, $location)->get();
                if(count($jobs) > 0){
                    return fractal()
                    ->collection($jobs)
                    ->parseIncludes(['category'])
                    ->transformWith(new JobsTransformer)
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
