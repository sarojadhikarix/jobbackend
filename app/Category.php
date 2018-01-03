<?php

namespace App;

use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Category extends Model
{
	use Orderable;

    protected $table = 'category';
    protected $fillable =['name'];
    protected $primaryKey = 'id';
    public $timestamp = false;

	public function jobs()
	{
		return $this->hasMany(Jobs::class)->whereDate('finish', '>=', Carbon::today()->toDateString())->orderBy('finish', 'asc');
	}
}
