<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManageReport;
use App\Models\Transaction;
use App\Models\MemberPaymentDetail;  // ← ADD THIS

class MemberController extends Controller
{
    public function dashboard()
    {
        $member = ManageReport::find(session('member_id'));

        if (!$member) {
            return redirect('/login')->with('status', 'Session expired. Please login again.');
        }

        session([
            'member_smart_pts' => $member->smart_point,
            'member_smart_qty' => $member->smart_quanity,
        ]);

        $purchases = DB::table('product_purchases')
                        ->where('member_id', $member->memberID)
                        ->orderByDesc('created_at')
                        ->get();

        return view('member.dashboard', compact('member', 'purchases'))
            ->with('status', $member->status);
    }

   public function profile()
{
    $memberId = session('member_id');
    $member   = ManageReport::find($memberId);

    if (!$member) {
        return redirect('/login')->with('status', 'Session expired. Please login again.');
    }

    $transactions = Transaction::where('member_id', $member->memberID)
        ->orderBy('created_at', 'desc')
        ->get();

    $creditTotal = DB::table('transaction')
        ->where('member_id', $member->memberID)
        ->where('type', 'Credit')
        ->sum(DB::raw('CAST(amount AS DECIMAL(10,2))'));

    $debitTotal = DB::table('transaction')
        ->where('member_id', $member->memberID)
        ->where('type', 'Debit')
        ->sum(DB::raw('CAST(amount AS DECIMAL(10,2))'));

    $smartWalletBalance = $creditTotal - $debitTotal;

    // ← Uses session member_id directly, same as store()
    $memberPayment = MemberPaymentDetail::where('member_id', $memberId)->first();

    return view('member.profile', compact(
        'member',
        'smartWalletBalance',
        'transactions',
        'memberPayment'
    ));
}

    public function logout(Request $request)
    {
        $request->session()->forget([
            'member_logged_in', 'member_id', 'member_memberID',
            'member_name', 'member_email', 'member_phone',
            'member_smart_pts', 'member_smart_qty', 'member_sponsor',
        ]);
        return redirect('/login')->with('status', 'You have been logged out.');
    }

    public function invoice($invoiceNo)
    {
        $member = ManageReport::find(session('member_id'));

        $purchase = DB::table('product_purchases')
                       ->where('invoice_no', $invoiceNo)
                       ->where('member_id', $member->memberID)
                       ->first();

        if (!$purchase) {
            abort(403, 'Invoice not found.');
        }

        return view('member.invoice', compact('member', 'purchase'));
    }
}
