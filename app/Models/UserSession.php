<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'country_code',
        'login_time',
        'logout_time',
        'duration_seconds'
    ];

    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
    ];

    // Calculate duration automatically when logout_time is set
    public function setLogoutTimeAttribute($value)
    {
        $this->attributes['logout_time'] = $value;
        $this->attributes['duration_seconds'] = $this->calculateDuration();
    }

    public function calculateDuration()
    {
        if ($this->login_time && $this->logout_time) {
            return $this->login_time->diffInSeconds($this->logout_time);
        }
        return null;
    }
}
