<?php

namespace App\Transformers;

use App\JobStatus;
use League\Fractal\TransformerAbstract;



class JobStatusTransformer extends TransformerAbstract{

    public function transform(JobStatus $jobstatus){
        return [
            'id' => (int) $jobstatus -> id,
            'user_id' => $jobstatus -> user_id,
            'job_id' => $jobstatus -> job_id,
            'status' => $jobstatus -> status
        ];
    }
}