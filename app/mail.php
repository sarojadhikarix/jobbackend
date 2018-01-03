<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $table = 'email';
    protected $fillable =['name','toemail','byemail','message','status'];
    protected $primaryKey = 'id';
    public $timestamp = true;
}
