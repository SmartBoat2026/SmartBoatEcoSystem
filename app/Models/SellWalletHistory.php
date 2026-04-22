<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellWalletHistory extends Model
{
    use HasFactory;

    protected $table = 'sell_wallet_histories';

    protected $fillable = [
        'member_id',
        'show_wallet_balance',
        'total_sell_wallet_balance',
        'payment_method',
        'mobile_number',
        'status',
    ];

    protected $casts = [
        'show_wallet_balance' => 'decimal:2',
        'total_sell_wallet_balance' => 'decimal:2',
        'payment_method' => 'integer',
        'status' => 'integer',
    ];
    public function member()
    {
        return $this->belongsTo(ManageReport::class, 'member_id', 'member_id');
    }
}
