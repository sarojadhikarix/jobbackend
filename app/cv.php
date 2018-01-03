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

	  public function scopeSearch($query, $keyword, $location){
      	
      	if($keyword != '' || $location != ''){
          return $query->where(function($query) use ($keyword){      

                          $searchKeyword = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
                          foreach ($searchKeyword as $val) {
                            $query->where('professional_title', 'like', '%' .$val. '%')
                                  ->orWhere('skills', 'like', '%' .$val. '%');
                              }
                          })
                       ->where(function($query) use ($location){

                          $searchLocation = preg_split('/\s+/', $location, -1, PREG_SPLIT_NO_EMPTY); 
                          foreach ($searchLocation as $val) {
                            $query->where('address', 'like', '%' .$val. '%');
                                }
                          }); 
                     }
 	 }

}
