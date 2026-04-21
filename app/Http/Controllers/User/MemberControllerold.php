<?php
// FILE: app/Http/Controllers/MemberController.php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\ManageReport;
use App\Models\ProductPurchase;   // use if you have this model

class MemberController extends Controller
{
    // ─── Member Dashboard ─────────────────────────────────────────────────────
    public function dashboard()
    {
        // Refresh member data from DB on each visit
        $member = ManageReport::find(session('member_id'));

        if (!$member) {
            return redirect('/login')->with('status', 'Session expired. Please login again.');
        }

        // Update session with latest points/qty in case admin changed them
        session([
            'member_smart_pts' => $member->smart_point,
            'member_smart_qty' => $member->smart_quanity,
        ]);

        // Fetch member's purchases (if you have product_purchases table)
        $purchases = \DB::table('product_purchases')
                        ->where('member_id', $member->memberID)
                        ->orderByDesc('created_at')
                        ->get();

        // ✅ status pass kar diya
        return view('member.dashboard', compact('member', 'purchases'))
            ->with('status', $member->status);
    }

    // ─── Member Profile ───────────────────────────────────────────────────────
    public function profile()
    {
        $member = ManageReport::find(session('member_id'));
        return view('member.profile', compact('member'));
    }

    // ─── Member Logout ────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $request->session()->forget([
            'member_logged_in', 'member_id', 'member_memberID',
            'member_name', 'member_email', 'member_phone',
            'member_smart_pts', 'member_smart_qty', 'member_sponsor',
        ]);
        return redirect('/login')->with('status', 'You have been logged out.');
    }

    // ─── Member Invoice View ──────────────────────────────────────────────────
    public function invoice($invoiceNo)
    {
        $member = ManageReport::find(session('member_id'));

        $purchase = \DB::table('product_purchases')
                       ->where('invoice_no', $invoiceNo)
                       ->where('member_id', $member->memberID)
                       ->first();

        if (!$purchase) {
            abort(403, 'Invoice not found.');
        }

        return view('member.invoice', compact('member', 'purchase'));
    }
}
