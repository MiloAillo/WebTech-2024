<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Administrator extends Authenticatable
{
    use HasApiTokens;
    
    protected $fillable = [
        "username",
        "password",
        "last_login_at",
        "created_at",
        "updated_at"
    ];

    protected $hidden = [
        "last_login_at",
        "created_at",
        "updated_at"
    ];

    public $timestamps = false;
}
