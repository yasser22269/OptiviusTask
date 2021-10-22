<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [];
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }



}
