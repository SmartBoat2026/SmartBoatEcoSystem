<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManageReport;
use Illuminate\Support\Facades\DB;

class ManageReportController extends Controller
{

    public function managereport()
    {
        $reports = DB::table('manage_reports as mr')
            ->leftJoin(
                DB::raw('(
                    SELECT
                        member_id COLLATE utf8mb4_general_ci AS member_id,
                        MIN(created_at) as first_purchase_date
                    FROM product_purchases
                    GROUP BY member_id
                ) as pp'),
                'mr.memberID', '=', 'pp.member_id'
            )
            // ── NEW JOIN ──────────────────────────────────────────────────
            ->leftJoin(
                DB::raw('(
                    SELECT
                        member_id COLLATE utf8mb4_general_ci AS member_id,
                        SUM(total_smartpoint)    AS total_smartpoint,
                        SUM(total_smartquantity) AS total_smartquantity
                    FROM product_purchases
                    GROUP BY member_id
                ) as sp'),
                'mr.memberID', '=', 'sp.member_id'
            )
            // ─────────────────────────────────────────────────────────────
            ->select([
                'mr.member_id',
                'mr.memberID',
                'mr.name',
                'mr.email',
                'mr.phone',
                'mr.password',
                'mr.transaction_password',
                'mr.sponser_id',
                'mr.sponser_name',
                'mr.joining_date',
                'mr.smart_point',
                'mr.amount',
                'mr.smart_wallet_balance',
                'mr.created_at as mr_created_at',
                'pp.first_purchase_date',
                // ── NEW COLUMNS ──────────────────────────────────────────
                DB::raw('COALESCE(sp.total_smartpoint, 0)    AS total_smartpoint'),
                DB::raw('COALESCE(sp.total_smartquantity, 0) AS total_smartquantity'),
                // ─────────────────────────────────────────────────────────
                DB::raw("
                    CASE
                        WHEN pp.first_purchase_date IS NOT NULL AND pp.first_purchase_date != ''
                            THEN pp.first_purchase_date
                        WHEN mr.created_at IS NOT NULL AND mr.created_at != ''
                            THEN mr.created_at
                        ELSE mr.joining_date
                    END as joining_member_date
                "),
            ])
            ->where('mr.status', 1)
            ->orderBy('mr.member_id', 'DESC')
            ->get();

        $memberMap = ManageReport::pluck('name', 'memberID')->toArray();

        foreach ($reports as $row) {
            $row->sponser_name_resolved = $memberMap[$row->sponser_id] ?? $row->sponser_name ?? '—';
        }

        return view('admin.managereport', compact('reports'));
    }
    
    
    public function memberactive()
    {
        $reports = DB::table('manage_reports as mr')
            ->select([
                'mr.member_id',
                'mr.memberID',
                'mr.name',
                'mr.email',
                'mr.phone',
                'mr.amount',
                'mr.verification_payment_screenshot',
                'mr.payment_utr_no',
                'mr.verification_message',
                'mr.status',
                'mr.sponser_id',
            ])
            ->where('status', 2)
            ->orderBy('mr.member_id', 'DESC')
            ->get();
            
        return view('admin.memberactive', compact('reports'));
    }
    
    public function toggleStatus(Request $request, $id)
    {
        $member = ManageReport::findOrFail($id);
    
        // 👇 AJAX se aaya hua status get karein
        $status = $request->status;
    
        // 👇 Direct set karein (toggle nahi)
        $member->status = $status;
        $member->save();
    
        return response()->json([
            'status'  => $member->status,
            'message' => 'Status updated successfully!'
        ]);
    }

    /**
     * Fetch members for live search (Admin ID selector with name/email/phone filter)
     * Route: GET /managereport/member-search?q=xxx
     */
    public function memberSearch(Request $request)
    {
        $q = $request->get('q', '');

        $members = ManageReport::where(function ($query) use ($q) {
                $query->where('memberID', 'LIKE', "%{$q}%")
                    ->orWhere('name',    'LIKE', "%{$q}%")
                    ->orWhere('email',   'LIKE', "%{$q}%")
                    ->orWhere('phone',   'LIKE', "%{$q}%");
            })
            ->select('memberID', 'name', 'email', 'phone')
            ->limit(20)
            ->get();

        return response()->json($members);
    }

    public function accessMember(Request $request)
    {
        $id = $request->input('member_id');

        $member = ManageReport::find($id);

        if (!$member) {
            return redirect()->back()->with('error', 'Member not found.');
        }

        // Store the member's data in session (matching MemberController login logic)
        session([
            'member_logged_in' => true,
            'member_id'        => $member->member_id,
            'memberID'         => $member->memberID,
            'member_name'      => $member->name,
            'member_email'     => $member->email,
            // Flag so you know admin accessed this account (optional but useful)
            'accessed_by_admin' => true,
        ]);

        return redirect('/member/dashboard');
    }

    public function store(Request $request)
    {
        // Server-side Terms check
        if (!$request->boolean('terms')) {
            return redirect()->back()
                ->withErrors(['terms' => 'You must accept the Terms & Conditions.'])
                ->withInput();
        }

        $memberID            = 'SB' . rand(1000000000, 9999999999);
        $password            = $this->generateRandomPassword();
        $transactionPassword = $this->generateRandomPassword();
        $joiningDate         = now()->format('d/m/Y');
        $referallCode        = $this->generateReferralCode();

        ManageReport::create([
            'memberID'             => $memberID,
            'name'                 => $request->name              ?? '',
            'phone'                => $request->phone             ?? '',
            'email'                => $request->email             ?? '',
            'sponser_id'           => $request->sponser_id        ?? 'M000001',
            'sponser_name'         => $request->sponser_name      ?? '',
            'joining_date'         => $joiningDate,
            'password'             => $password,
            'transaction_password' => $transactionPassword,
            'smart_point'          => 0,
            'smart_quanity'        => '',
            'created_at'           => now()->format('Y-m-d H:i:s'),
            'age'                  => $request->age    ?? '',
            'gender'               => $request->gender ?? '',
            'referral_code'        => $referallCode,
            'smart_wallet_balance' => 0,
        ]);

        return redirect()->back()->with([
            'success'      => 'Member registered successfully!',
            'new_memberID' => $memberID,
            'new_pass'     => $password,
            'new_password' => $transactionPassword,
            'new_name'     => $request->name,
            'new_age'      => $request->age    ?? '',
            'new_gender'   => $request->gender ?? '',
        ]);
    }

    private function generateRandomPassword(): string
    {
        $chars  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';
        for ($i = 0; $i < 7; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $result;
    }
    
    //REFERAL CODE GENERATER
    private function generateReferralCode($length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';
        
        for ($i = 0; $i < $length; $i++) {
            $result += $chars.charAt(Math.floor(Math.random() * $chars.length));
        }
        
        return $result;
    }

    public function update(Request $request, $id)
    {
        $report = ManageReport::find($id);

        if (!$report) {
            return redirect()->back()->with('error', 'Record Not Found!');
        }

        $joining_date = $request->joining_date;
        if ($joining_date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $joining_date)) {
            $joining_date = \Carbon\Carbon::createFromFormat('Y-m-d', $joining_date)->format('d/m/Y');
        }

        $report->update([
            'memberID'             => $request->memberID,
            'name'                 => $request->name,
            'phone'                => $request->phone                ?? '',
            'email'                => $request->email                ?? '',
            'password'             => $request->password             ?? $report->password,
            'transaction_password' => $request->transaction_password ?? $report->transaction_password,
            'sponser_id'           => $request->sponser_id           ?? '',
            'sponser_name'         => $request->sponser_name         ?? '',
            'joining_date'         => $joining_date                  ?? $report->joining_date,
            'smart_point'          => $request->smart_point          ?? 0,
        ]);

        return redirect()->back()->with('success', 'Record Updated Successfully!');
    }

    public function delete($id)
    {
        $report = ManageReport::find($id);

        if (!$report) {
            return redirect()->back()->with('error', 'Record Not Found!');
        }

        $report->delete();
        return redirect()->back()->with('success', 'Record Deleted Successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $raw = $request->input('ids');

        // Handle array input: ids[] from new blade
        if (is_array($raw)) {
            $ids = array_filter(array_map('intval', $raw), fn($id) => $id > 0);
        }
        // Handle comma-separated string: ids from old blade
        elseif (is_string($raw) && !empty(trim($raw))) {
            $ids = array_filter(array_map('intval', explode(',', $raw)), fn($id) => $id > 0);
        }
        else {
            return redirect()->back()->with('error', 'No records selected for deletion.');
        }

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Invalid selection.');
        }

        $deleted = ManageReport::whereIn('member_id', $ids)->delete();

        return redirect()->back()->with('success', $deleted . ' record(s) deleted successfully!');
    }
}
