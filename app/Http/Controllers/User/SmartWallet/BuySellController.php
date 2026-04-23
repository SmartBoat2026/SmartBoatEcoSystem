<?php

namespace App\Http\Controllers\User\SmartWallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManageReport;
use App\Models\SellWalletHistory;

class BuySellController extends Controller
{
    public function selfSell()
    {    
        return view('member.smartwallet.selfSell');
    }
    public function selfSellStore(Request $request)
    {
        // Validate the request data
        $request->validate([
            'wallet_balance' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:1,2,3,4',
            'mobile_number' => 'nullable|string|max:15',
        ]);

        $memberMemberID = session('member_memberID');
        $userData = ManageReport::where('memberID', $memberMemberID)->first();

        if (!$userData) {
            return response()->json([
                'message' => 'Member not found'
            ], 404);
        }

        $smart_wallet_balance = $userData->smart_wallet_balance ?? 0;
        if ($smart_wallet_balance < $request->wallet_balance) {
            return response()->json([
                'message' => 'Insufficient smart wallet balance'
            ], 422);
        }

        SellWalletHistory::create([
            'member_id' => $userData->member_id,
            'show_wallet_balance' => $request->input('wallet_balance'),
            'total_sell_wallet_balance' => 0, 
            'payment_method' => $request->input('payment_method'),
            'mobile_number' => $request->input('mobile_number'),
            'status' => 1, 
        ]);
        return response()->json([
            'message' => 'Self-sell details submitted successfully',
        ]);
    }
    public function selfSellListData(Request $request)
    {
        $MemberId = session('member_memberID');
        $member_id = ManageReport::where('memberID', $MemberId)->value('member_id');

        $query = SellWalletHistory::with('member')
            ->where('member_id', $member_id)
            ->latest();

        // AJAX check
        if (!$request->ajax()) {
            return view('member.smartwallet.sender');
        }

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();

        $data = collect($records)->map(function ($row,$index) {

            return [
                // 'checkbox' => '<input type="checkbox" class="row-checkbox" value="'.$row->id.'">',
                'DT_RowIndex' => $index + 1,
                'show_wallet_balance' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->show_wallet_balance, 2).'
                            </span>',
                'mobile_number' => $row->mobile_number,
                'created_at' => optional($row->created_at)->format('d M Y h:i A'),
                'payment_method' => $row->payment_method == 1 ? 'UPI Transfer via QR Code' : ($row->payment_method == 2 ? 'UPI Number' : ($row->payment_method == 3 ? 'Bank to Bank Transfer' : ($row->payment_method == 4 ? 'Cash to Bank Transfer' : 'Unknown'))),

                'total_sell_wallet_balance' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->total_sell_wallet_balance, 2).'
                            </span>',

                'status' => $this->formatStatus($row->status),

            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    private function formatStatus($status)
    {
        $map = [
            2 => ['Closed','bg-warning'],
            1 => ['Active','bg-success'],
            3 => ['Cancelled','bg-danger'],
            
        ];

        $s = $map[$status] ?? ['Unknown','bg-dark'];

        return '<span class="badge '.$s[1].'">'.$s[0].'</span>';
    }
    
}
