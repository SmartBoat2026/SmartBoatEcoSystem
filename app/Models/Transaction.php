<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Table name (kyunki default plural hota hai: transactions)
    protected $table = 'transaction';

    // Primary key
    protected $primaryKey = 'id';

    // Timestamps disable (kyunki table me updated_at nahi hai)
    public $timestamps = false;

    // Fillable fields
    protected $fillable = [
        'member_id',
        'added_by_id',
        'amount',
        'action',
        'type',
        'status',
        'created_at'
    ];

    // Casts (optional but recommended)
    protected $casts = [
        'amount'      => 'float',
        'added_by_id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
    ];
}