<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            ->where('passive_bonus_status', 0)
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
            ->select('member_id,id', DB::raw('SUM(total_smartquantity) as total_quantity'))
            ->where('created_at', '>=', DB::raw('NOW() - INTERVAL 1 HOUR'))
            ->where('passive_bonus_status', 0)
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
            
            // 👉 Update passive_bonus_status = 1
            DB::table('product_purchases')
                ->where('id', $member->id)
                ->update(['passive_bonus_status' => 1]);
        
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
}