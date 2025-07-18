<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'body',
        'allowed_countries',
        'start_time',
        'end_time',
        'is_active'
    ];
    protected $casts = [
        'allowed_countries' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function scopeForCountry($query, $countryCode)
    {
        return $query->where(function ($q) use ($countryCode) {
            $q->whereNull('allowed_countries')
                ->orWhereJsonContains('allowed_countries', $countryCode);
        });
    }

    public function scopeCurrentlyActive($query)
    {
        $now = now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_time')
                ->orWhere('start_time', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_time')
                ->orWhere('end_time', '>=', $now);
        });
    }
}
