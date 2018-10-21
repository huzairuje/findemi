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

    public function sql()
    {
        return  $this->select(self::all($this->table)
        );
    }

}
