<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKay = 'id';

    protected $fillable = [
        'name',
        'description',
    ];
}
