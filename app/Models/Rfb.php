<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Rfb extends Model
{
    protected $fillable = [
        'member_id',
        'amount',
        'status',
        'rfb_id',
        'no_of_sellers'
    ];

    // Buyer
    public function buyer()
    {
        return $this->belongsTo(ManageReport::class, 'member_id');
    }

    // Sellers (child)
    public function sellers()
    {
        return $this->hasMany(RfbSeller::class, 'rfb_id');
    }
}