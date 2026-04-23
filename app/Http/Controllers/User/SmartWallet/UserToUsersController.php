<?php

namespace App\Http\Controllers\User\SmartWallet;

use App\Http\Controllers\Controller;  

use App\Models\ManageReport;
use App\Models\SmartWalletUserToUser; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class UserToUsersController extends Controller
{
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START SMART WALLET SENDER LANDING PAGE-----------------------------------------------------------------
    public function sender()
    {    
        return view('member.smartwallet.sender');
    }
    //---------------------------------------------------END SMART WALLET SENDER LANDING PAGE-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START SENDER TABLE DATA SHOW-----------------------------------------------------------------
    public function senderList(Request $request)
    {
        $senderMemberId = session('member_memberID');
        $sender_member_id = ManageReport::where('memberID', $senderMemberId)->value('member_id');

        $query = SmartWalletUserToUser::with('receiver')
            ->where('sender_member_id', $sender_member_id)
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
                'member' => "
                        <div style='font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;'>
                            ".ucwords(strtolower($row->receiver->name ?? 'Unknown Member'))."
                        </div>

                        ".(!empty($row->receiver_member_id) ? "
                            <div style='font-size:11px;color:#0c447c;margin-top:2px;'>
                                <span style='background:#e6f1fb;padding:1px 7px;border-radius:12px;'>
                                    {$row->receiver_member_id}
                                </span>
                            </div>
                        " : "")."
                    ",
                'date' => optional($row->created_at)->format('d M Y h:i A'),

                'amount' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->request_balance, 2).'
                            </span>',

                'status' => $this->formatStatusForSenderList($row->status),

                'actions' => $this->formatActionsForSenderList($row),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    private function formatStatusForSenderList($status)
    {
        $map = [
            1 => ['Pending','bg-warning'],
            2 => ['Accepted','bg-success'],
            5 => ['Confirmed','bg-success'],
            6 => ['Received','bg-success'],
            3 => ['Rejected','bg-danger'],
            4 => ['Cancelled','bg-secondary'],
        ];

        $s = $map[$status] ?? ['Unknown','bg-dark'];

        return '<span class="badge '.$s[1].'">'.$s[0].'</span>';
    }
    private function formatActionsForSenderList($row)
    {
        return '<button class="btn btn-sm btn-info message-btn"
                    data-sender="'.$row->sender_member_id.'"
                    data-receiver="'.$row->receiver_member_id.'"
                    data-id="'.$row->id.'">
                    <i class="bi bi-chat-dots"></i>
                </button>';
    }
    //---------------------------------------------------END SENDER TABLE DATA SHOW-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START SHOW MEMBER LIST ON SENDER NEW SENDING WALLET bALANCE FORM-----------------------------------------------------------------
    public function getMembers(Request $request)
    {
        $loggedInMemberId = session('member_memberID');

        $query = ManageReport::with('activeMember')
            ->where('memberID', '!=', $loggedInMemberId);
        $requestMember_id = trim($request->member_id);
        if ($requestMember_id) {
            $query->where(function ($q) use ($requestMember_id) {
                $q->where('memberID', 'like', '%' . $requestMember_id . '%')
                ->orWhere('name', 'like', '%' . $requestMember_id . '%');
            });
        }

        $members = $query->select('memberID', 'name')
            ->orderByRaw('LOWER(TRIM(name)) ASC')
            ->get();
        $selfwalletBalance = ManageReport::where('memberID', $loggedInMemberId)->value('smart_wallet_balance');
        return response()->json([
            'status' => true,
            'results' => $members,
            'selfwalletBalance'=>$selfwalletBalance,
        ]);
    }
    //---------------------------------------------------END SHOW MEMBER LIST ON SENDER NEW SENDING WALLET bALANCE FORM-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START SMART WALLET SENDER NEW SENDING WALLET bALANCE FORM SUBMIT-----------------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'member_id'       => 'required|exists:manage_reports,memberID',
            'request_balance' => 'required|numeric|min:1',
            'wallet_balance'  => 'required|numeric|min:0',
            'transaction_password' => 'required'
        ]);

        $senderMemberId = session('member_memberID');
        $receiverMemberId = $request->member_id;
       
        if ($senderMemberId == $receiverMemberId) {
            return back()->withErrors([
                'member_id' => 'You cannot send wallet balance to yourself.'
            ]);
        }
        // check transaction password (session user)
        $user = ManageReport::where('memberID', $senderMemberId)->first();

        // if (!$user || !\Hash::check($request->transaction_password, $user->transaction_password)) {
        if (!$user || ($request->transaction_password !== $user->transaction_password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid transaction password'
            ], 422);
        }

        $walletBalance = floatval($request->wallet_balance);
        $requestAmount = floatval($request->request_balance);

        if ($requestAmount > $walletBalance) {
            return back()->withErrors([
                'request_balance' => 'Sent amount exceeds wallet balance.'
            ]);
        }
        DB::beginTransaction();

        try {
             $sender_member_id = ManageReport::where('memberID', $senderMemberId)->value('member_id');
             $receiver_member_id = ManageReport::where('memberID', $receiverMemberId)->value('member_id');

            SmartWalletUserToUser::create([
                'sender_member_id'   => $sender_member_id,
                'receiver_member_id' => $receiver_member_id,
                'wallet_balance'     => $walletBalance,
                'request_balance'    => $requestAmount,
                'status'             => 5, // confirm
            ]);
            // update sender wallet (deduct)
            $user->smart_wallet_balance = $user->smart_wallet_balance - $requestAmount;
            $user->save();
            Transaction::create([
                'member_id'    => $senderMemberId,
                'added_by_id'  => $user->member_id,
                'amount'       => $requestAmount,
                'debit'        => 0,
                'credit'       => $requestAmount,
                'action'       => 'Smart Wallet Balance Sent',
                'type'         => 'Debit',
                'status'       => 1,
                'created_at'   => now(),
            ]);

            // update receiver wallet (add)
            $receiver = ManageReport::where('memberID', $receiverMemberId)->first();

            if ($receiver) {
                $receiver->smart_wallet_balance = $receiver->smart_wallet_balance + $requestAmount;
                $receiver->save();
                Transaction::create([
                    'member_id'    => $receiverMemberId,
                    'added_by_id'  => $user->member_id,
                    'amount'       => $requestAmount,
                    'debit'        => 0,
                    'credit'       => $requestAmount,
                    'action'       => 'Samart Wallet Balance Received',
                    'type'         => 'Credit',
                    'status'       => 1,
                    'created_at'   => now(),
                ]);
            }        
            DB::commit();
            $receiverName = $receiver->name ?? 'Member';

            $message = "Wallet balance sent successfully to <b>{$receiverName}</b> (<b>{$receiverMemberId}</b>) ₹"
            . number_format($requestAmount, 2);

            
            return response()->json([
                'status' => true,
                'message' => $message,
                'selfwalletBalance' => number_format($user->smart_wallet_balance, 2),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your request. Please try again.'
            ], 500);
        }
        
    }
    //---------------------------------------------------START SMART WALLET SENDER NEW SENDING WALLET bALANCE FORM SUBMIT-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START DELETE ONE ROW FOR SENDER REQUEST HISTORY TABLE-----------------------------------------------------------------
    public function deleteOne($id)
    {
        try {

            $item = SmartWalletUserToUser::findOrFail($id);
            $item->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Record deleted successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //---------------------------------------------------END DELETE ONE ROW FOR SENDER REQUEST HISTORY TABLE-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START BULK DELETE FOR SENDER REQUEST HISTORY TABLE-----------------------------------------------------------------
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No records selected'
            ], 400);
        }

        try {

            SmartWalletUserToUser::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' records deleted successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
    //---------------------------------------------------END BULK DELETE FOR SENDER REQUEST HISTORY TABLE-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    public function receiver()
    {
        return view('member.smartwallet.receiver');
    }

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------START RECEIVER TABLE DATA SHOW-----------------------------------------------------------------
    
    public function receiverList(Request $request)
    {
        $receiverMemberId = session('member_memberID');
        $receiver_member_id = ManageReport::where('memberID', $receiverMemberId)->value('member_id');

        $query = SmartWalletUserToUser::with('sender')
            ->where('receiver_member_id', $receiver_member_id)
            ->latest();

        // AJAX check
        if (!$request->ajax()) {
            return view('member.smartwallet.receiver');
        }

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();

        $data = collect($records)->map(function ($row,$index) {

            return [
                
                'DT_RowIndex' => $index + 1,
                'member' => "
                        <div style='font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;'>
                            ".ucwords(strtolower($row->sender->name ?? 'Unknown Member'))."
                        </div>

                        ".(!empty($row->receiver_member_id) ? "
                            <div style='font-size:11px;color:#0c447c;margin-top:2px;'>
                                <span style='background:#e6f1fb;padding:1px 7px;border-radius:12px;'>
                                    {$row->receiver_member_id}
                                </span>
                            </div>
                        " : "")."
                    ",
                'date' => optional($row->created_at)->format('d M Y h:i A'),

                'amount' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->request_balance, 2).'
                            </span>',

                'status' => $this->formatStatusForReceiverList($row->status),

                'actions' => $this->formatActionsForReceiverList($row),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    public function formatStatusForReceiverList($status)
    {
        $map = [
            1 => ['Pending','bg-warning'],
            2 => ['Accepted','bg-success'],
            6 => ['Confirmed','bg-success'],
            5 => ['Received','bg-success'],
            3 => ['Rejected','bg-danger'],
            4 => ['Cancelled','bg-secondary'],
        ];

        $s = $map[$status] ?? ['Unknown','bg-dark'];

        return '<span class="badge '.$s[1].'">'.$s[0].'</span>';
    }   
    private function formatActionsForReceiverList($row)
    {
        return '<button class="btn btn-sm btn-info message-btn"
                    data-sender="'.$row->receiver_member_id.'"
                    data-receiver="'.$row->sender_member_id.'"
                    data-id="'.$row->id.'">
                    <i class="bi bi-chat-dots"></i>
                </button>';
    }
    //---------------------------------------------------END RECEIVER TABLE DATA SHOW-----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
}
