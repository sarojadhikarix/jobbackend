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

  public function scopeSearch($query, $keyword, $location){
      if($keyword != '' || $location != ''){
          return $query->where(function($query) use ($keyword){
                          $query->where('title', 'like', '%' .$keyword. '%')
                                ->orWhere('keywords', 'like', '%' .$keyword. '%')
                                ->orWhere('company_name', 'like', '%' .$keyword. '%');
                          })
                       ->where(function($query) use ($location){
                          $query->where('city', 'like', '%' .$location. '%')
                                ->orWhere('district', 'like', '%' .$location. '%')
                                ->orWhere('zone', 'like', '%' .$location. '%')
                                ->orWhere('country', 'like', '%' .$location. '%');
                          })
                       ->whereDate('finish', '>=', Carbon::today()->toDateString())
                       ->orderBy('finish', 'asc');
                       
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