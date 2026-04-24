<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdmindirectbonusController extends Controller
{
    public function index()
    {
        $bonuses = DB::table('bonus')
            ->where('bonus_type', 'Direct')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.admindirectbonus', compact('bonuses'));
    }
}
