<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKay = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'title',
        'text',
        'created_by',
        'post_id',
        'parent_id ',
        'community_id',
    ];

    protected $casts = [
        'is_one_trip' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function community()
    {
        return $this->belongsTo(Interest::class, 'community_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function parent(){
        return $this->hasOne( Comment::class, 'id', 'parent_id' );
    }

    public function children(){
        return $this->hasMany( Comment::class, 'parent_id', 'id' );
    }

}
