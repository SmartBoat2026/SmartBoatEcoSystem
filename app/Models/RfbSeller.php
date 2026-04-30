<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfbSeller extends Model
{
    protected $fillable = [
        'rfb_id',
        'member_id',
        'seller_member_id',
        'status'
    ];

    // Parent request
    public function rfb()
    {
        return $this->belongsTo(Rfb::class, 'rfb_id');
    }

    // Seller info
    public function seller()
    {
        return $this->belongsTo(ManageReport::class, 'seller_member_id');
    }
    public function sellId()
    {
        return $this->belongsTo(SellWalletHistory::class, 'sell_history_id');
    }
}
