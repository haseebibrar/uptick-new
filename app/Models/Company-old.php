<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use Notifiable;

    protected $guard = 'teacher';

    protected $fillable = [
        'name', 'phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}