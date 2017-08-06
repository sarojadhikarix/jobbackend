<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryBriefTransformer extends TransformerAbstract
{
	public function transform(Category $category)
	{
		return[
			'name' => $category->name,
			'id' => $category->id,
		];
	}
}