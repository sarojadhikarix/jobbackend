<?php

namespace App\Transformers;

use App\Jobs;
use League\Fractal\TransformerAbstract;

class JobsTransformer extends TransformerAbstract{

protected $availableIncludes = ['category'];

    public function transform(Jobs $jobs){
        return [
            'id' => (int) $jobs -> id,
            'title' => $jobs -> title,
            'description' => $jobs -> description,
            'category_id' => $jobs -> category_id,
            'company_name' => $jobs -> company_name,
            'company_website' => $jobs -> company_website,
            'company_email' => $jobs -> company_email,
            'company_phone' => $jobs -> company_phone,
            'company_logo' => $jobs -> company_logo,
            'company_facebook' => $jobs -> company_facebook,
            'company_video' => $jobs -> company_video,
            'keywords' => $jobs -> keywords,
            'type' => $jobs	-> type,
            'requirements' => $jobs -> requirements,
            'user_id' => $jobs -> category,
            'updated_at' => $jobs -> updated_at,
            'created_at' => $jobs -> created_at,
            'finish' => $jobs -> finish,
            'city' => $jobs -> city,
            'district' => $jobs -> district,
            'country' => $jobs -> country,
            'zone' => $jobs -> zone,
            'status' => $jobs -> status,
            'filled' => $jobs -> filled,
            'applicant_count' => $jobs -> applicant_count
        ];
    }

    public function includeCategory(Jobs $job)
    {
        if($job->category != null)
        {
            return $this->item(
                $job->category,
                new CategoryBriefTransformer
                );
        }
    }


}