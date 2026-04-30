<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminpassivebonusController extends Controller
{
    public function passivebonus()
    {
        $bonuses = DB::table('bonus')
            ->where('bonus_type', 'Passive')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.adminpassivebonus', compact('bonuses'));
    }
}
// SB7450454564
// JG2G4SV
