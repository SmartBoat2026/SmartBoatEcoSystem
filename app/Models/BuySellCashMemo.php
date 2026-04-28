<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuySellCashMemo extends Model
{
    protected $table = 'buy_sell_cash_memos';

    protected $fillable = [
        'sender_member_id',
        'admin_member_id',
        'amount',
        'qr_file',
        'transaction_id',
        'status',
        'comment',
        'rfb_id',
        'sell_history_id'
    ];
    public function sender()
    {
        return $this->belongsTo(ManageReport::class, 'sender_member_id', 'member_id');
    }
}
