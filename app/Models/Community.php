<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $table = 'communities';
    protected $primaryKay = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name',
        'description',
        'image_banner_url',
        'lat',
        'lon',
        'address_from_map',
        'base_camp_address',
        'created_by',
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'user_community')
            ->withTimestamps();
    }

    public function interest()
    {
        return $this->belongsToMany('App\Models\Interest', 'community_interest')
            ->withTimestamps();
    }

    public function activity()
    {
        return $this->belongsToMany('App\Models\Activity', 'activity_community')
            ->withTimestamps();
    }

    public function post()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function event()
    {
        return $this->belongsToMany('App\Models\Event', 'event_community')
            ->withTimestamps();
    }

}
