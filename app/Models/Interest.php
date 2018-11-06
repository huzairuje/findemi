<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    protected $table = 'interests';
    protected $primaryKay = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name',
        'description',
    ];

    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'user_interest')
            ->withTimestamps();
    }

    public function event()
    {
        return $this->belongsToMany('App\Models\Event', 'event_interest')
            ->withTimestamps();
    }

    public function community()
    {
        return $this->belongsToMany('App\Models\Community', 'community_interest')
            ->withTimestamps();
    }

    public function activity()
    {
        return $this->belongsToMany('App\Models\Activity', 'activity_interest')
            ->withTimestamps();
    }

}
