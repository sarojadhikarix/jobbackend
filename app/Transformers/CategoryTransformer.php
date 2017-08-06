<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;



class CategoryTransformer extends TransformerAbstract{

protected $availableIncludes = ['jobs'];

    public function transform(Category $category){
        return [
            'id' => (int) $category -> id,
            'name' => $category -> name
        ];
    }

    public function includeJobs(Category $category)
	{
		return $this->collection(
			$category->jobs, 
			new JobsTransformer);
	}
}