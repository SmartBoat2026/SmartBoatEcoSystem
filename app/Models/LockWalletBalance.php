<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockWalletBalance extends Model
{
    // Table name (kyunki default plural hota hai: transactions)
    protected $table = 'lock_wallet_balances';

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
        'created_at',
        'transaction_id', // ← NEW FIELD
    ];

    // Casts (optional but recommended)
    protected $casts = [
        'amount'      => 'float',
        'added_by_id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
    ];
}
