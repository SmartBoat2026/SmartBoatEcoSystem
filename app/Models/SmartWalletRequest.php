<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartWalletRequest extends Model
{
    use HasFactory;

    protected $table = 'smart_wallet_requests';

    protected $fillable = [
        'sender_member_id',
        'receiver_member_id',
        'wallet_balance',
        'request_balance',
        'status',
    ];
    public function receiver()
    {
        return $this->belongsTo(ManageReport::class, 'receiver_member_id', 'memberID');
    }
    public function sender()
    {
        return $this->belongsTo(ManageReport::class, 'sender_member_id', 'memberID');
    }
}