<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonus';

    protected $primaryKey = 'id';

    // bonus table has only created_at (no updated_at)
    public $timestamps = false;

    protected $fillable = [
        'bonus_type',
        'memberID',
        'member_name',
        'passive_bonusamt',
        'status',
    ];

    protected $casts = [
        'passive_bonusamt' => 'decimal:2',
        'status'           => 'integer',
        'created_at'       => 'datetime',
    ];
}
