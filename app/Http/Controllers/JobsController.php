<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Jobs;
USE App\JobStatus;
use Illuminate\Support\Str;
use App\Transformers\JobsTransformer;
use App\Transformers\JobStatusTransformer;
use Carbon\Carbon;
use App\User;
use App\Transformers\UserTransformer;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $jobs = Jobs::whereDate('finish', '>=', Carbon::today()->toDateString())->orderBy('finish', 'asc')->where('status', '=', 1)->get();
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
        $this->validate(request(), [
            'title' => 'required | min:5',
            'description' => 'required | min:100',
            'category_id' => 'required',
            'company_name' => 'required',
            'company_email' => 'required',
            'company_phone' => 'required | min:10',
            'keywords' => 'required | min:5',
            'type' => 'required',
            'requirements' => 'required | min:100',
            'user_id' => 'required',
            'finish' => 'required',
            'city' => 'required | min:3',
            'country' => 'required | min:3',
        ]); 


        $job = new jobs;
        $job->title = $request->title;
        $job->description = $request->description;
        $job->category_id = $request->category_id;
        $job->company_name = $request->company_name;
        $job->company_email = $request->company_email;
        $job->company_phone = $request->company_phone;
        $job->keywords = $request->keywords;
        $job->type = $request->type;
        $job->requirements = $request->requirements;
        $job->user_id = $request->user_id;
        $job->finish = $request->finish;
        $job->city = $request->city;
        $job->country = $request->country;
        $job->status = 0;

        if(empty($request->company_website))
            $job->company_website = '';
        else
         $job->company_website = $request->company_website;

        if(empty($request->company_logo))
            $job->company_logo = '';
        else
         $job->company_logo = $request->company_logo;

        if(empty($request->company_facebook))
            $job->company_facebook = '';
        else
         $job->company_facebook = $request->company_facebook;

        if(empty($request->company_video))
            $job->company_video = '';
        else
         $job->company_video = $request->company_video;

        if(empty($request->district))
            $job->district = '';
        else
         $job->district = $request->district;

        if(empty($request->zone))
            $job->zone = '';
        else
         $job->zone = $request->zone;

        if(empty($request->created_at))
            $job->created_at = Carbon::now();
        else
         $job->created_at = $request->created_at;

        $job->updated_at = Carbon::now();
        $job->filled = 0;
        $job->applicant_count = 0;

     try{
            $job->save();
        } catch (\PDOException $e){
            $returnData = array(
                'message' => 'Something worng! Please contact support center.'
            );

            return response()->json($returnData, 500);
        }

        $returnData = array(
            'message' => 'Job successfully added.'
        );

        return response()->json($returnData, 200);
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

    public function findByUser($id)
    {
        $jobs = Jobs::where('user_id', '=', $id)->orderBy('id', 'desc')->get();

        if(count($jobs)){
            return fractal()
            ->collection($jobs)
            ->parseIncludes([])
            ->transformWith(new JobsTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'No jobs found!']
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
                    'status' => 'No jobs found!']
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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


     public function findJobStatus(Request $request)
    {
        $job_id = $request->job_id;
        $user_id = $request->user_id;
        $stats = NULL;
        $statsusers = NULL;

        if($job_id != '' && $user_id != '')
        {
            $stats = JobStatus::where('job_id', '=', $job_id)->where('user_id', '=', $user_id)->orderBy('id', 'desc')->get();
        }else if($job_id == '' && $user_id != ''){
            $stats = JobStatus::where('user_id', '=', $user_id)->orderBy('id', 'desc')->get();
        }else if($job_id != '' && $user_id == ''){
            $statsusers = JobStatus::where('job_id', '=', $job_id)->orderBy('id', 'desc')->get();
        }

        if(count($stats)){
            for($i = 0; $i < count($stats); $i++){
            $jobs[$i] =  Jobs::find($stats[$i]->job_id);
            }

            if(count($jobs)){
                return fractal()
                ->collection($jobs)
                ->parseIncludes(['category'])
                ->transformWith(new JobsTransformer)
                ->toArray();
            }
            else{
                return response()->json([
                    'data' => [
                        'status' => 'Job Deleted or changed.']
                        ], 404);
                }
        }
        else if(count($statsusers)){
            for($i = 0; $i < count($statsusers); $i++){
            $users[$i] =  User::find($statsusers[$i]->user_id);
            }

            if(count($users)){
                return fractal()
                ->collection($users)
                ->parseIncludes([])
                ->transformWith(new UserTransformer)
                ->toArray();
            }
            else{
                return response()->json([
                    'data' => [
                        'status' => 'User Deleted or changed.']
                        ], 404);
                }

            }
        else
            {
                return response()->json([
                    'data' => [
                        'status' => 'none']
                    ], 404);
            }
    }

     public function updateJobStatus(Request $request)
    {
        try{
        $edited_character = JobStatus::where('user_id', $request->$id)->update([
            'status' => $request->status
        ]);
        }catch (\PDOException $e){
            $returnData = array(
                'message' => 'Could not update.'
            );
            return response()->json($returnData, 422);

        }
            $returnData = array(
                'message' => 'Updated.'
            );

        return response()->json($returnData, 200);
    }

     public function addJobStatus(Request $request)
    {
        $this->validate(request(), [
            'user_id' => 'required',
            'job_id' => 'required',
            'status' => 'required',
        ]); 


        $jobStatus = new JobStatus;
        $jobStatus->user_id = $request->user_id;
        $jobStatus->job_id = $request->job_id;
        $jobStatus->status = $request->status;

        $stats = JobStatus::where('job_id', '=', $request->job_id)->where('user_id', '=', $request->user_id)->get();

        if(count($stats)>= 1){
            $returnData = array(
                'message' => 'Already applied.'
            );

            return response()->json($returnData, 422);
        }else{
        
     try{
            $jobStatus->save();
        } catch (\PDOException $e){
            $returnData = array(
                'message' => 'Error while adding.'
            );

            return response()->json($returnData, 422);
        }

        $returnData = array(
            'message' => 'Status successfully added.'
        );

        return response()->json($returnData, 200);
    }
    }

    public function update(Request $request)
    {
        try{
        Jobs::where('id', $request->id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'company_phone' => $request->company_phone,
            'keywords' => $request->keywords,
            'type' => $request->type,
            'requirements' => $request->requirements,
            'finish' => $request->finish,
            'city' => $request->city,
            'country' => $request->country,
            'status' => $request->status,
            'company_website' => $request->company_website,
            'company_logo' => $request->company_logo,
            'company_facebook' => $request->company_facebook,
            'company_video' => $request->company_video,
            'district' => $request->district,
            'zone' => $request->zone,
            'filled' => $request->filled,
            'applicant_count' => $request->applicant_count
        ]);
        }catch (\PDOException $e){
            $returnData = array(
                'message' => 'Could not update.'
            );
            return response()->json($returnData, 422);

        }
            $returnData = array(
                'message' => 'Updated.'
            );

        return response()->json($returnData, 200);
    }
}

