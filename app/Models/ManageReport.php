<?php
// FILE: app/Models/ManageReport.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageReport extends Model
{
    protected $table      = 'manage_reports';
    protected $primaryKey = 'member_id';
    public    $timestamps = false;

    protected $fillable = [
        'memberID',
        'name',
        'phone',
        'password',
        'email',
        'sponser_id',
        'sponser_name',
        'joining_date',
        'transaction_password',
        'smart_point',
        'smart_quanity',
        'created_at',
        'age',
        'gender',
        'smart_wallet_balance',
    ];

    /**
     * Login can be done with memberID OR name + password.
     * Passwords in manage_reports are plain-text (e.g. '123456', 'TL8BO1S').
     */
    public function verifyPassword(string $password): bool
    {
        return $this->password === $password;
    }
    public function activeMember()
    {
        return $this->belongsTo(ProductPurchaseList::class, 'memberID', 'member_id')
                    ->where('product_dp', '>=', 1.00);
    }
}
