<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cv extends Model
{
    protected $table = 'cv';
   	protected $primaryKey = 'id';
   	protected $fillable = ['user_id', 'professional_title', 'address', 'skills', 'wish', 'cv_link', 'status'];

	public $timestamps = true;

	  public function user()
	  {
	     return $this->belongsTo(User::class);
	  }
}
