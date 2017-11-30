<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Jobs extends Model
{
    protected $table = 'jobs';
    protected $fillable =['title', 'description', 'category', 'company_name', 'company_website', 'company_email', 'company_phone', 'company_logo', 'company_facebook', 'company_video', 'keywords', 'type', 'requirements', 'user_id', 'updated_at', 'created_at', 'finish', 'city', 'district', 'zone', 'country'];
    protected $primaryKey = 'id';
    public $timestamp = false;



  public function category()
  {
     return $this->belongsTo(Category::class);
  }

  // public function scopeSearch($query, $keyword, $location){
  //     if($keyword != '' || $location != ''){
  //         return $query->where(function($query) use ($keyword){
  //                         $query->where('title', 'like', '%' .$keyword. '%')
  //                               ->orWhere('keywords', 'like', '%' .$keyword. '%')
  //                               ->orWhere('company_name', 'like', '%' .$keyword. '%');
  //                         })
  //                      ->where(function($query) use ($location){
  //                         $query->where('city', 'like', '%' .$location. '%')
  //                               ->orWhere('district', 'like', '%' .$location. '%')
  //                               ->orWhere('zone', 'like', '%' .$location. '%')
  //                               ->orWhere('country', 'like', '%' .$location. '%');
  //                         })
  //                      ->whereDate('finish', '>=', Carbon::today()->toDateString())
  //                      ->orderBy('finish', 'asc');
                       
  //                    }
  // }

    public function scopeSearch($query, $keyword, $location){
      if($keyword != '' || $location != ''){
 
        

          return $query->where(function($query) use ($keyword){      

                          $searchKeyword = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
                          foreach ($searchKeyword as $val) {
                            $query->where('title', 'like', '%' .$val. '%')
                                  ->orWhere('keywords', 'like', '%' .$val. '%')
                                  ->orWhere('company_name', 'like', '%' .$val. '%');
                              }
                          })
                       ->where(function($query) use ($location){

                          $searchLocation = preg_split('/\s+/', $location, -1, PREG_SPLIT_NO_EMPTY); 
                          foreach ($searchLocation as $val) {
                            $query->where('city', 'like', '%' .$val. '%')
                                  ->orWhere('district', 'like', '%' .$val. '%')
                                  ->orWhere('zone', 'like', '%' .$val. '%')
                                  ->orWhere('country', 'like', '%' .$val. '%');
                                }
                          })
                       ->whereDate('finish', '>=', Carbon::today()->toDateString())
                       ->orderBy('finish', 'asc')->where('status', '=', 1);
                       
                     }
  }

}
                       // ->where('city', 'like', '%' .$location. '%')
                       // ->orWhere('district', 'like', '%' .$location. '%')
                       // ->orWhere('zone', 'like', '%' .$location. '%')
                       // ->orWhere('country', 'like', '%' .$location. '%')
                       // ->orWhere('title', 'like', '%' .$keyword. '%')
                       // ->orWhere('keywords', 'like', '%' .$keyword. '%')
                       // ->orWhere('company_name', 'like', '%' .$keyword. '%');