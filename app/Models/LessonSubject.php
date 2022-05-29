<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LessonSubject extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'focusarea_id',
    ];

    public function homeworks()
    {
        return $this->hasOne(HomeWork::class, 'lesson_id');
    }
    
    public function focusarea()
    {
        return $this->belongsTo(FocusArea::class);
    }
}
