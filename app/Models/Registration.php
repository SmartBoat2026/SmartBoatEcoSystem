<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table      = 'manage_reports';
    protected $primaryKey = 'member_id';
    public    $timestamps = false;

    protected $fillable = [
        'memberID',
        'name',
        'phone',
        'country_code',
        'email',
        'age',
        'gender',
        'password',
        'transaction_password',
        'sponser_id',
        'sponser_name',
        'joining_date',
        'smart_point',
        'smart_quanity',
        'status',
        'amount',
        'referral_code',
        'referral_by',
        'smart_wallet_balance',
        'verification_payment_screenshot',
        'payment_utr_no',
        'smart_wallet_balance',
        'verification_message',
        'created_at',
    ];

    public static function generateMemberID(): string
    {
        do {
            $id = 'SB' . rand(1000000000, 9999999999);
        } while (self::where('memberID', $id)->exists());

        return $id;
    }

    public static function generatePassword(): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $pass  = '';
        for ($i = 0; $i < 7; $i++) {
            $pass .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $pass;
    }
}
