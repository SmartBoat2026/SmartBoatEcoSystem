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
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> cfff5f07947a8bf512e80723df7ccf0697277a77
    public function senderUser()
    {
        return $this->belongsTo(ManageReport::class, 'sender_member_id', 'member_id');
    }
    public function receiverUser()
    {
        return $this->belongsTo(ManageReport::class, 'receiver_member_id', 'member_id');
    }
<<<<<<< HEAD
=======
>>>>>>> Pingki
>>>>>>> cfff5f07947a8bf512e80723df7ccf0697277a77
}