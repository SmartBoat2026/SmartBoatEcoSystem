<?php
// app/Models/ProductPurchase.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchase extends Model
{
    use HasFactory;

    protected $table = 'product_purchases';

    protected $fillable = [
        'member_id',
        'added_by_id',
        'invoice_no',
        'purchase_date',
        'total',
        'total_smartpoint',
        'total_smartquantity',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(ProductPurchaseList::class, 'purchase_id');
    }

    //  FIX — ownerKey must be 'memberID' (varchar), NOT 'member_id' (int PK)
    public function member()
    {
        return $this->belongsTo(ManageReport::class, 'member_id', 'memberID');
    }
}