<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPaymentDetail extends Model
{
    protected $table = 'member_payment_details';

    protected $fillable = [
        'member_id',
        'account_holder',
        'account_number',
        'bank_name',
        'ifsc_code',
        'branch_name',
        'account_type',
        'upi_id',
        'upi_mobile',
        'upi_app',
        'qr_code',
    ];

}
