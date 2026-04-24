<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DirectbonusController extends Controller
{
    /**
     * Member panel: direct bonus earned by logged-in member (bonus.member_id = sponsor).
     */
    public function directbonus()
    {
        $memberID = session('member_memberID');

        $bonuses = DB::table('bonus')
            ->where('member_id', $memberID)
            ->where('bonus_type', 'Direct')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.directbonus', compact('bonuses'));
    }
}
