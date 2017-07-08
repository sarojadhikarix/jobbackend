<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table = 'jobs';
    protected $fillable =['title', 'description', 'category', 'company_name', 'company_website', 'company_email', 'company_phone', 'company_logo', 'company_facebook', 'company_video', 'keywords', 'type', 'requirements', 'user_id', 'updated_at', 'created_at', 'finish', 'city', 'district', 'zone', 'country'];
    protected $primaryKey = 'id';
    public $timestamp = false;
}
