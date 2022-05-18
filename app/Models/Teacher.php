<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable
{
    use Notifiable;

    protected $guard = 'teacher';

    protected $fillable = [
        'name', 'email', 'password', 'image', 'expertise', 'phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}