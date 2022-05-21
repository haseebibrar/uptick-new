<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FocusArea extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name'
    ];
    public function lessonsubjects()
    {
        return $this->hasMany(LessonSubject::class);
    }
}
