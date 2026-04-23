<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ManageReport;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class SmartwalletController extends Controller
{
    public function smartwallet()
    {
        $reports = DB::table('transactions as t')
            ->leftJoin('manage_reports as m', 'm.memberID', '=', 't.member_id')
            ->select(
                't.*',
                'm.name',
                'm.email',
                'm.phone',
                'm.smart_wallet_balance'
            )
            ->orderBy('t.id', 'desc')
            ->get();

        return view('admin.smartwallet', compact('reports'));
    }

    public function store(Request $request)
    {
        // ── Validation ────────────────────────────────────────────────────
        $request->validate([
            'sponser_id' => 'required|exists:manage_reports,memberID',
            'amount'     => 'required|numeric|min:0.01',
        ]);

        // ── Find Member ───────────────────────────────────────────────────
        $report = ManageReport::where('memberID', $request->sponser_id)->firstOrFail();

        // ── Update Smart Wallet Balance ───────────────────────────────────
        $report->update([
            'smart_wallet_balance' => $report->smart_wallet_balance + $request->amount
        ]);

        // ── Insert Transaction Record ─────────────────────────────────────
        Transaction::create([
            'member_id'   => $request->sponser_id,
            'added_by_id' => session('admin_id') ?? 1,  // ← use real admin session
            'amount'      => $request->amount,
            'action'      => 'Smart Wallet Balance',
            'type'        => 'Credit',
            'status'      => 1,
            'created_at'  => now(),
        ]);

        return redirect()->back()->with([
            'success'      => 'Smart Wallet Balance Added Successfully!',
            'new_memberID' => $request->sponser_id,
            'new_name'     => $request->sponser_name,
            'amount'       => $request->amount,
        ]);
    }
}
