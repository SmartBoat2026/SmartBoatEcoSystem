<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MemberPaymentDetail;
// use App\Models\ManageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberPaymentController extends Controller
{
    // public function store(Request $request)
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

    //     // ── Get member_id directly from session ──────────────────────────────
    //     $memberId = session('member_id');

    //     if (!$memberId) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Session expired. Please login again.'
    //         ], 401);
    //     }

    //     $data = $request->only([
    //         'account_holder',
    //         'account_number',
    //         'bank_name',
    //         'ifsc_code',
    //         'branch_name',
    //         'account_type',
    //         'upi_id',
    //         'upi_mobile',
    //         'upi_app',
    //     ]);

    //     if (!empty($data['ifsc_code'])) {
    //         $data['ifsc_code'] = strtoupper($data['ifsc_code']);
    //     }

    //     // ── QR code upload ───────────────────────────────────────────────────
    //     if ($request->hasFile('qr_code')) {
    //         $existing = MemberPaymentDetail::where('member_id', $memberId)->first();
    //         if ($existing && $existing->qr_code) {
    //             Storage::disk('public')->delete($existing->qr_code);
    //         }
    //         $path = $request->file('qr_code')->store('member_qr_codes', 'public');
    //         $data['qr_code'] = $path;
    //     }

    //     // ── updateOrCreate using session member_id directly ──────────────────
    //     $payment = MemberPaymentDetail::updateOrCreate(
    //         ['member_id' => $memberId],   // ← direct session value, no model lookup
    //         $data
    //     );

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Payment details saved successfully.',
    //         'data'    => $payment,
    //     ]);
    // }

    // public function show()
    // {
    //     $memberId = session('member_id');

    //     if (!$memberId) {
    //         return response()->json(['success' => false, 'message' => 'Session expired.'], 401);
    //     }

    //     $payment = MemberPaymentDetail::where('member_id', $memberId)->first();

    //     return response()->json([
    //         'success' => true,
    //         'data'    => $payment,
    //     ]);
    // }
}
