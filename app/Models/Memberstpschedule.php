<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Memberstpschedule extends Model
{
    protected $table = 'member_stp_schedules';

    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'added_by_id',
        'start_date',
        'end_date',
        'running_hrs',
        'per_hrs_amount',
        'per_day_amount',
        'total_amount',
        'status',
        'created_at',
    ];
}
