<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKay = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name',
        'description',
        'image_banner_url',
        'is_paid',
        'start_date',
        'end_date',
        'lat',
        'lon',
        'address_from_map',
        'tag',
        'created_by',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_ended' => 'boolean',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_event')
            ->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function interest()
    {
        return $this->belongsToMany(Interest::class, 'event_interest')
            ->withTimestamps();
    }

    public function community()
    {
        return $this->belongsToMany(Community::class, 'event_community')
            ->withTimestamps();
    }

}
