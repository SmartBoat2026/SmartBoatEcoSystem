<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonusController extends Controller
{
    public function insertPassiveBonus()
    {
        // ── Step 1: Get admin settings ────────────────────────────────────────
        $admin = DB::table('admin')->first();

        if (!$admin) {
            return response()->json(['error' => 'Admin settings not found.'], 500);
        }

        $passiveBonusRate = 0.20;

        // ── Step 2: Calculate Bonus Pool ──────────────────────────────────────
        $bonusPool = DB::table('product_purchases')
            ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 1 HOUR'))
            ->sum('total_smartpoint');

        $bonusPool = $bonusPool * $passiveBonusRate;

        if ($bonusPool <= 0) {
            return response()->json(['message' => 'No purchases in the last hour. No bonus inserted.']);
        }

        // ── Step 3: Calculate Accumulated Quantity ────────────────────────────
        $accumulatedQuantity = DB::table('product_purchases')
            ->sum('total_smartquantity');

        if ($accumulatedQuantity <= 0) {
            return response()->json(['error' => 'Accumulated quantity is zero. Cannot divide.'], 500);
        }

        // ── Step 4: Per-unit Rate ─────────────────────────────────────────────
        $rate = $bonusPool / $accumulatedQuantity;

        // ── Step 5: Get each member's quantity in last 1 hour ─────────────────
        $memberQuantities = DB::table('product_purchases')
            ->select('member_id', DB::raw('SUM(total_smartquantity) as total_quantity'))
            ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 1 HOUR'))
            ->groupBy('member_id')
            ->get();

        if ($memberQuantities->isEmpty()) {
            return response()->json(['message' => 'No members found for bonus.']);
        }

        // ── Step 6: Insert Bonus Records ──────────────────────────────────────
        $insertedCount = 0;

        foreach ($memberQuantities as $member) {

        $bonusAmount = $member->total_quantity * $rate;

            DB::table('bonus')->insert([
                'bonus_type'     => 'Passive',
                'member_id'      => $member->member_id,
                'total_quantity' => $member->total_quantity,
                'rate'           => round($rate, 4),
                'bonus_amount'   => $bonusAmount,
                'status'         => 1,
                'created_at'     => now(),
            ]);

            $insertedCount++;
        }

        return response()->json([
            'message'         => 'Passive bonus inserted successfully.',
            'bonus_pool'      => $bonusPool,
            'rate_per_unit'   => $rate,
            'members_bonused' => $insertedCount,
        ]);
    }

    public function index()
    {
        $bonuses = DB::table('bonus')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.bonus.index', compact('bonuses'));
    }

    /** Admin: all direct bonus rows. */
    public function adminDirectBonus()
    {
        $bonuses = DB::table('bonus')
            ->where('bonus_type', 'Direct')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.admindirectbonus', compact('bonuses'));
    }

    /** Member panel: direct bonus for logged-in sponsor (bonus.member_id). */
    public function memberDirectBonus()
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
