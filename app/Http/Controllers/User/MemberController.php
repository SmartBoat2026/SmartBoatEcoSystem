<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\ManageReport;
use App\Models\Transaction;
use App\Models\MemberPaymentDetail;

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

    // public function profile()
    // {
    //     $memberId = session('member_id');
    //     $member   = ManageReport::find($memberId);

    //     if (!$member) {
    //         return redirect('/login')->with('status', 'Session expired. Please login again.');
    //     }

    //     $transactions = Transaction::where('member_id', $member->memberID)
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $creditTotal = DB::table('transaction')
    //         ->where('member_id', $member->memberID)
    //         ->where('type', 'Credit')
    //         ->sum(DB::raw('CAST(amount AS DECIMAL(10,2))'));

    //     $debitTotal = DB::table('transaction')
    //         ->where('member_id', $member->memberID)
    //         ->where('type', 'Debit')
    //         ->sum(DB::raw('CAST(amount AS DECIMAL(10,2))'));

    //     $smartWalletBalance = $creditTotal - $debitTotal;

    //     $memberPayment = MemberPaymentDetail::where('member_id', $memberId)->first();

    //     return view('member.profile', compact(
    //         'member',
    //         'smartWalletBalance',
    //         'transactions',
    //         'memberPayment'
    //     ));
    // }

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

    $creditTotal = DB::table('transactions')
        ->where('member_id', $member->memberID)
        ->where('type', 'Credit')
        ->sum(DB::raw('CAST(amount AS DECIMAL(10,2))'));

    $debitTotal = DB::table('transactions')
        ->where('member_id', $member->memberID)
        ->where('type', 'Debit')
        ->sum(DB::raw('CAST(amount AS DECIMAL(10,2))'));

    $smartWalletBalance = $creditTotal - $debitTotal;

    // Fetch ALL payment records (not just first)
    $memberPayments = MemberPaymentDetail::where('member_id', $memberId)
        ->orderBy('id', 'desc')
        ->get();

    return view('member.profile', compact(
        'member',
        'smartWalletBalance',
        'transactions',
        'memberPayments'   // <-- changed from $memberPayment to $memberPayments
    ));
}

    // ── PAYMENT: Store / Update ───────────────────────────────────────────────
    // public function paymentStore(Request $request)
    // {
    //     $request->validate([
    //         'account_holder' => 'nullable|string|max:255',
    //         'account_number' => 'nullable|string|max:50',
    //         'bank_name'      => 'nullable|string|max:255',
    //         'ifsc_code'      => 'nullable|string|max:20',
    //         'branch_name'    => 'nullable|string|max:255',
    //         'account_type'   => 'nullable|in:savings,current',
    //         'upi_id'         => 'nullable|string|max:100',
    //         'upi_mobile'     => 'nullable|string|max:15',
    //         'upi_app'        => 'nullable|in:gpay,phonepe,paytm,bhim,other',
    //         'qr_code'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    //     ]);

    //     $memberId = session('member_id');

    //     if (!$memberId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Session expired. Please login again.'
    //         ], 401);
    //     }

    //     $data = $request->only([
    //         'account_holder', 'account_number', 'bank_name',
    //         'ifsc_code', 'branch_name', 'account_type',
    //         'upi_id', 'upi_mobile', 'upi_app',
    //     ]);

    //     if (!empty($data['ifsc_code'])) {
    //         $data['ifsc_code'] = strtoupper($data['ifsc_code']);
    //     }

    //     if ($request->hasFile('qr_code')) {
    //         $existing = MemberPaymentDetail::where('member_id', $memberId)->first();
    //         if ($existing && $existing->qr_code) {
    //             Storage::disk('public')->delete($existing->qr_code);
    //         }
    //         $data['qr_code'] = $request->file('qr_code')->store('member_qr_codes', 'public');
    //     }

    //     $payment = MemberPaymentDetail::updateOrCreate(
    //         ['member_id' => $memberId],
    //         $data
    //     );

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Payment details saved successfully.',
    //         'data'    => $payment,
    //     ]);
    // }


    public function paymentStore(Request $request)
{
    $request->validate([
        'account_holder' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:50',
        'bank_name'      => 'nullable|string|max:255',
        'ifsc_code'      => 'nullable|string|max:20',
        'branch_name'    => 'nullable|string|max:255',
        'account_type'   => 'nullable|in:savings,current',
        'upi_id'         => 'nullable|string|max:100',
        'upi_mobile'     => 'nullable|string|max:15',
        'upi_app'        => 'nullable|in:gpay,phonepe,paytm,bhim,other',
        'qr_code'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    ]);

    $memberId = session('member_id');
    if (!$memberId) {
        return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    }

    $data = $request->only([
        'account_holder', 'account_number', 'bank_name',
        'ifsc_code', 'branch_name', 'account_type',
        'upi_id', 'upi_mobile', 'upi_app',
    ]);
    $data['member_id'] = $memberId;

    if (!empty($data['ifsc_code'])) {
        $data['ifsc_code'] = strtoupper($data['ifsc_code']);
    }

    $editId = $request->input('edit_id');

    if ($editId) {
        // UPDATE existing record
        $payment = MemberPaymentDetail::where('id', $editId)
                        ->where('member_id', $memberId)
                        ->firstOrFail();
        if ($request->hasFile('qr_code')) {
            if ($payment->qr_code) Storage::disk('public')->delete($payment->qr_code);
            $data['qr_code'] = $request->file('qr_code')->store('member_qr_codes', 'public');
        }
        $payment->update($data);
    } else {
        // INSERT new record
        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('member_qr_codes', 'public');
        }
        $payment = MemberPaymentDetail::create($data);
    }

    // Return the new/updated row HTML for dynamic injection
    $qrUrl = $payment->qr_code ? asset('public/storage/' . $payment->qr_code) : null;

    return response()->json([
        'success' => true,
        'message' => $editId ? 'Updated successfully.' : 'Saved successfully.',
        'row'     => [
            'id'             => $payment->id,
            'account_holder' => $payment->account_holder ?: '-',
            'bank_name'      => $payment->bank_name       ?: '-',
            'account_number' => $payment->account_number  ?: '-',
            'ifsc_code'      => $payment->ifsc_code       ?: '-',
            'upi_id'         => $payment->upi_id          ?: '-',
            'upi_app'        => strtoupper($payment->upi_app ?? '-'),
            'qr_url'         => $qrUrl,
        ],
    ]);
}

public function paymentDestroy(Request $request)
{
    $memberId = session('member_id');
    if (!$memberId) {
        return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    }

    $id = $request->input('id');
    $payment = MemberPaymentDetail::where('id', $id)
                    ->where('member_id', $memberId)
                    ->firstOrFail();

    if ($payment->qr_code) {
        Storage::disk('public')->delete($payment->qr_code);
    }
    $payment->delete();

    return response()->json(['success' => true, 'message' => 'Deleted successfully.', 'id' => $id]);
}

    // ── PAYMENT: Delete ───────────────────────────────────────────────────────
    // public function paymentDestroy()
    // {
    //     $memberId = session('member_id');

    //     if (!$memberId) {
    //         return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    //     }

    //     $payment = MemberPaymentDetail::where('member_id', $memberId)->first();

    //     if (!$payment) {
    //         return response()->json(['success' => false, 'message' => 'No payment details found.'], 404);
    //     }

    //     if ($payment->qr_code) {
    //         Storage::disk('public')->delete($payment->qr_code);
    //     }

    //     $payment->delete();

    //     return response()->json(['success' => true, 'message' => 'Payment details deleted successfully.']);
    // }

    // ── PAYMENT: Show (API) ───────────────────────────────────────────────────
    public function paymentShow()
    {
        $memberId = session('member_id');

        if (!$memberId) {
            return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
        }

        $payment = MemberPaymentDetail::where('member_id', $memberId)->first();

        return response()->json(['success' => true, 'data' => $payment]);
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
