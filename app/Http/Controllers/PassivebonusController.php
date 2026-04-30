<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PassivebonusController extends Controller
{
    public function passivebonus()
    {
        // session('member_memberID') = "SB7936896584" format
        $memberID = session('member_memberID');

        $bonuses = DB::table('bonus')
            ->where('member_id', $memberID)
            ->where('bonus_type', 'Passive')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.passivebonus', compact('bonuses'));
    }
}
