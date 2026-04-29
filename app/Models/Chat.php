<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'sender_member_id',
        'receiver_member_id',
        'message',
        'file',
        'type',
        'is_seen'
    ];
    public function senderUser()
    {
        return $this->belongsTo(ManageReport::class, 'sender_member_id', 'member_id');
    }
    public function receiverUser()
    {
        return $this->belongsTo(ManageReport::class, 'receiver_member_id', 'member_id');
    }
}