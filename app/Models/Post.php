<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $primaryKay = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'title',
        'text',
        'created_by',
        'community_id',
    ];

    protected $casts = [
        'is_one_trip' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function community()
    {
        return $this->belongsTo('App\Models\Interest', 'community_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment');
    }
}
