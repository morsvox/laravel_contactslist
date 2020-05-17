<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        "name",
        "middlename",
        "lastname",
        "phone",
        "favorite"
    ];

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

}
