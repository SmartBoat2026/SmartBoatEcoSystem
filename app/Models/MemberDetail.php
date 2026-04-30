<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $table = 'member_details';

    protected $primaryKey = 'member_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false; // because you didn't use created_at & updated_at

    protected $fillable = [
        'regionbelongs',
        'isFranchisee',
        'memberID',
        'sponser_name',

        'smart_point',
        'smart_quanity',

        'company_autopool_income',
        'wallet_balance',
        'wallet_balance_top_up',
        'award_fund',
        'travel_fund',
        'car_fund',

        'market_expensive',
        'active_bonus',
        'consistency_bonus',
        'super_active_bonus',
        'delex_active_bonus',
        'flag_ship_bonus',

        'salutation',
        'name',
        'date_of_birth',
        'email',
        'countryname',
        'phone',
        'alt_phone',

        'username',
        'password',
        'transaction_password',

        'authorization_ID',

        'sponser_id',
        'referenceID',
        'position',
        'signup_package',
        'epin',

        'address',
        'city',
        'joining_date',

        'placement_leg',
        'placementID',
        'binary_referenceID',
        'bianry_palcementLeg',

        'bank_name',
        'bank_acc_no',
        'bank_brn_name',
        'profile_pic',
        'ifsc_code',

        'nominee_name',
        'nominee_relation',
        'id_proof',

        'rank',

        'registration_date_time',
        'ip_address',

        'status',
        'balance_update_afer_login',
        'updatedProfile',

        'remark',
        'last_updated_by',

        'panNumber',
        'completeProfile',
        'user_role',

        'date_created',
        'state',
        'pin_code',

        'repeated',
        'added_from_website',

        'fileToUpload_adhar',
        'fileToUpload_pan',
        'fileToUpload_kyc',

        'referenceIDNo',

        'pv_carry_forward_left',
        'pv_carry_forward_right',
        'current_pv_left',
        'current_pv_right',
        'total_pv_left',
        'total_pv_right',

        'current_bv_left',
        'current_bv_right',
        'total_bv_left',
        'total_bv_right',

        'sequence',

        'bv_carry_forward_right',
        'bv_carry_forward_left',

        'adhar_front_image',
        'adhar_back_image',
        'pan_card',
        'my_photo',
        'passbook',

        'kyc_approved',
        'performed_by',

        'recharge_wallet',

        'blocking',

        'wallet_1',
        'wallet_2',

        'global_sponser_id',

        'level_details',

        'phone_pay',
        'google_pay',

        'entry_club_member',

        'payment_details',
        'transaction_id',
        'transaction_screenshot',

        'smbank_name',
        'smbank_acc_no',
        'smbank_brn_name',
        'smbank_ifsc_code',
        'qr_code',

        'registration_datetime',
        'staking_coin'
    ];

    protected $hidden = [
        'password',
        'transaction_password'
    ];
}