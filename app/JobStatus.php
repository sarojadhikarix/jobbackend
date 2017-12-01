<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    protected $table = 'job_status';
   	protected $primaryKey = 'id';
   	protected $fillable = ['user_id', 'job_id', 'status'];

	public $timestamps = true;
}
