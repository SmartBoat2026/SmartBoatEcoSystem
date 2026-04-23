<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartWalletCompanyPayment extends Model
{
    use HasFactory;

    protected $table = 'smart_wallet_company_payments';

    protected $fillable = [
        'sender_member_id',
        'admin_member_id',
        'amount',
        'qr_file',
        'transaction_id',
        'status',
        'comment',
    ];
    public function sender()
    {
        return $this->belongsTo(ManageReport::class, 'sender_member_id', 'member_id');
    }
}