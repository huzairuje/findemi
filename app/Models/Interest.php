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
        return $this->belongsToMany(User::class, 'user_interest')
            ->withTimestamps();
    }

    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_interest')
            ->withTimestamps();
    }

    public function community()
    {
        return $this->belongsToMany(Community::class, 'community_interest')
            ->withTimestamps();
    }

    public function activity()
    {
        return $this->belongsToMany(Activity::class, 'activity_interest')
            ->withTimestamps();
    }

}
