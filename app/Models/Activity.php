<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    protected $primaryKay = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'lat',
        'lon',
        'address_from_map',
        'address',
        'tag',
        'created_by',
    ];

    protected $casts = [
        'is_one_trip' => 'boolean'
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_activity')
            ->withTimestamps();
    }

    public function interest()
    {
        return $this->belongsToMany(Interest::class, 'activity_interest')
            ->withTimestamps();
    }

    public function community()
    {
        return $this->belongsToMany(Community::class, 'activity_community')
            ->withTimestamps();
    }

}
