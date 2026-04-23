<?php
// app/Models/ProductPurchaseList.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchaseList extends Model
{
    use HasFactory;

    protected $table = 'product_purchaselists';

    protected $fillable = [
        'purchase_id',
        'member_id',
        'product_id',
        'product_name',
        'product_hsn',
        'product_baseprice',
        'product_dp',
        'product_count',
        'product_smartpoints',
        'product_smartqty',
        'product_total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(ProductPurchase::class, 'purchase_id');
    }
}