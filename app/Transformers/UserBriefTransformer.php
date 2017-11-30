<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserBriefTransformer extends TransformerAbstract
{
	public function transform(User $user)
	{
		return[
            'id' => (int) $user -> id,
            'name' => $user -> name,
            'role_id' => $user -> role_id,
            'email' => $user -> email,
		];
	}
}