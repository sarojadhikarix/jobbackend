<?php

namespace App\Traits;

trait Orderable
{
	public function scopeLatestFirst($query)
	{
		return $query->whereDate('finish', '>=', Carbon::today()->toDateString())->latest()->where('status', '1')->get();
	}

	public function scopeOldestFirst($query)
	{
		return $query->orderBy('created_at', 'asc');
	}
}