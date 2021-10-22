<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;

class Profile extends Authenticatable implements MustVerifyEmail
{
    use   Notifiable;
    protected $guarded = [];


    protected $hidden = [
        'password', 'remember_token','api_token',
    ];


    // set hashing Password

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
   }

   // Relations
   public function article()
   {

       return $this->hasMany(Article::class, 'profile_id');
   }

}
