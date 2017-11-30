<?php

namespace App\Transformers;

use App\cv;
use League\Fractal\TransformerAbstract;

class CVTransformer extends TransformerAbstract{

protected $availableIncludes = ['user'];

    public function transform(cv $cv){
        return [
            'id' => (int) $cv -> id,
            'user_id' => $cv -> user_id,
            'professional_title' => $cv -> professional_title,
            'address' => $cv -> address,
            'skills' => $cv -> skills,
            'wish' => $cv -> wish,
            'cv_link' => $cv -> cv_link,
            'status' => $cv -> status,
            'updated_at' => $cv -> updated_at
        ];
    }

    public function includeUser(cv $cv)
	{
            return $this->item(
                $cv->user,
                new UserBriefTransformer
                );
    }
}