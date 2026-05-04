<?php

namespace App\Http\Controllers\User\SmartWallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManageReport;
use App\Models\SellWalletHistory;
use App\Models\MemberDetail;
use App\Models\Rfb;
use App\Models\RfbSeller;
use App\Models\Transaction;
use App\Models\LockWalletBalance;
use App\Models\BuySellCashMemo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BuySellController extends Controller
{
    public function selfSell()
    {    
        return view('member.smartwallet.selfSell');
    }

    public function selfSellStore(Request $request)
    {
        $request->validate([
            'wallet_balance' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:1,2,3,4',
            'mobile_number' => 'nullable|string|max:15',

            // 'qr_image' => 'required_if:payment_method,1|nullable|image|mimes:jpg,jpeg,png|max:2048',
            'payment_details' => 'required_if:payment_method,2,3,4|nullable|string|max:255',
        ]);
        if ($request->payment_method == 1 && $request->type != 'r') {
            $rules['qr_image'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $memberMemberID = session('member_memberID');

        $userData = ManageReport::where('memberID', $memberMemberID)->first();

        if (!$userData) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        if (($userData->smart_wallet_balance ?? 0) < $request->wallet_balance) {
            return response()->json(['message' => 'Insufficient smart wallet balance'], 422);
        }

        $editId = $request->input('edit_id');

        // ================== QR Upload ==================
        $qrPath = null;

        if ($request->payment_method == 1 && $request->hasFile('qr_image') && $request->type != 'r') {

            $folderPath = public_path('uploads/qr');

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }

            $file = $request->file('qr_image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($folderPath, $fileName);

            $qrPath = 'uploads/qr/' . $fileName;
        }
        else{
            $qrPath=$request->qr_image;
        }

        // ================== UPDATE ==================
        if ($editId) {

            $row = SellWalletHistory::find($editId);

            if (!$row) {
                return response()->json(['message' => 'Data not found'], 404);
            }
            $hasSeller = RfbSeller::where('sell_history_id', $editId)->where('status', 1)->exists();
            if ($hasSeller && $request->payment_method != $row->payment_method) {
                return response()->json([
                    'message' => 'Payment method cannot be changed because Buyer Request already exists'
                ], 422);
            }

            if ($request->wallet_balance < $row->total_sell_wallet_balance ) {
                return response()->json([
                    'message' => 'Wallet balance must be at least sold'
                ], 422);
            }
            // old QR delete if new QR uploaded OR switching method
            if ($row->qr_image && ($qrPath || $request->payment_method != 1)) {

                $oldPath = public_path($row->qr_image);

                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $row->update([
                'show_wallet_balance' => $request->wallet_balance,
                'mobile_number' => $request->mobile_number,
                'payment_method' => $request->payment_method,

                'qr_image' => $request->payment_method == 1
                    ? ($qrPath ?? $row->qr_image)
                    : null,

                'payment_details' => $request->payment_method != 1
                    ? $request->payment_details
                    : null,
            ]);
            if($request->wallet_balance == $row->total_sell_wallet_balance)
            {
                $this->selfSellCancel($editId);
            }

            return response()->json([
                'message' => $row->sell_id . ' updated successfully'
            ]);
        }

        // ================== CREATE ==================
        $active = SellWalletHistory::where('member_id', $userData->member_id)
            ->where('status', 1)
            ->first();

        if ($active) {
            return response()->json([
                'message' => 'You already have an active self-sell details. Please cancel/close it before creating a new one.'
            ], 422);
        }

        $count = SellWalletHistory::where('member_id', $userData->member_id)->count();

        $sellId = 'SELL-' . session('member_memberID') . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

            SellWalletHistory::create([
                'sell_id' => $sellId,
                'member_id' => $userData->member_id,
                'show_wallet_balance' => $request->wallet_balance,
                'total_sell_wallet_balance' => 0,
                'payment_method' => $request->payment_method,
                'mobile_number' => $request->mobile_number,
                'qr_image' => $qrPath,
                'payment_details' => $request->payment_method != 1 ? $request->payment_details : null,
                'status' => 1,
            ]);

            return response()->json([
                'message' => $sellId . ': Self-sell details submitted successfully',
            ]);
    }
    public function selfSellListData(Request $request)
    {
        $MemberId = session('member_memberID');
        $member_id = ManageReport::where('memberID', $MemberId)->value('member_id');

        $query = SellWalletHistory::with('member')
            ->where('member_id', $member_id)->orderByDesc('status')
            ->orderByDesc('created_at')->orderByDesc('id');

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();

        $data = collect($records)->map(function ($row,$index) {

            return [
                'DT_RowIndex' => $index + 1,
                'sell_id' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">'.$row->sell_id.'</span>',
                'show_wallet_balance' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->show_wallet_balance, 2).'
                            </span>',
                'mobile_number' => $row->mobile_number,
                'created_at' => optional($row->created_at)->format('d M Y h:i A'),
                'payment_method' => $row->payment_method == 1 ? 'UPI Transfer via QR Code' : ($row->payment_method == 2 ? 'UPI Number' : ($row->payment_method == 3 ? 'Bank to Bank Transfer' : ($row->payment_method == 4 ? 'Cash to Bank Transfer' : 'Unknown'))),

                'total_sell_wallet_balance' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->total_sell_wallet_balance, 2).'
                            </span>',
                'payment_details' => $row->qr_image 
                            ? '<div><img src="'.asset('public/'.$row->qr_image).'" width="60" height="60"></div>'
                            : '<span class="text-muted">'.($row->payment_details ?? 'N/A').'</span>',
                'status' => $this->formatStatus($row->status),
                'actions' => '
                                <div class="d-flex gap-2 justify-content-center">

                                    <!-- VIEW (only if balance > 0) -->
                                    '.($row->total_sell_wallet_balance > 0 ? '
                                        <button type="button"
                                            class="btn btn-sm btn-outline-secondary view-btn"
                                            data-sell-history-id="'.$row->id.'"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    ' : '').'

                                    <!-- STATUS = 1 -->
                                    '.($row->status == 1 ? '

                                        <!-- CANCEL -->
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger cancelled-self-sell"
                                            data-sell-history-id="'.$row->id.'"
                                            title="Close Sell">
                                            <i class="bi bi-x-circle"></i>
                                        </button>

                                        <!-- EDIT -->
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary edit-btn"
                                            data-sell-history-id="'.$row->id.'"
                                            title="Edit Sell">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                    ' : '

                                        <!-- RENEW -->
                                        <button type="button"
                                            class="btn btn-sm btn-outline-success renew-btn"
                                            data-sell-history-id="'.$row->id.'" data-wallet-balance="'.$row->show_wallet_balance.'" data-payment-method="'.$row->payment_method.'" data-qr-image="'.$row->qr_image.'" data-payment-details="'.$row->payment_details.'" data-mobile-number="'.$row->mobile_number.'"
                                            title="Renew Sell">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>

                                    ').'

                                </div>
                            '

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
            2 => ['Sell Completed','bg-primary'],
            1 => ['Active','bg-success'],
            3 => ['Closed Sell','bg-danger'],            
        ];

        $s = $map[$status] ?? ['Unknown','bg-dark'];

        return '<span class="badge '.$s[1].'">'.$s[0].'</span>';
    }
    public function selfSellCancel($id)
    {
        $sell = SellWalletHistory::find($id);

        if (!$sell) {
            return response()->json([
                'message' => 'Sell request not found'
            ], 404);
        }

        if ($sell->status != 1) {
            return response()->json([
                'message' => 'Only active sell requests can be cancelled'
            ], 422);
        }

        $existRequest = RfbSeller::where('sell_history_id', $sell->id)
            ->where('status', 1)
            ->exists();

        if ($existRequest) {

            RfbSeller::where('sell_history_id', $sell->id)
                ->where('status', 1)
                ->update(['status' => 4]);

            $rfbIds = RfbSeller::where('sell_history_id', $sell->id)
                ->pluck('rfb_id')
                ->unique();

            foreach ($rfbIds as $rfbId) {

                $hasActiveSeller = RfbSeller::where('rfb_id', $rfbId)
                    ->where('status', '!=', 4)
                    ->exists();

                if (!$hasActiveSeller) {
                    Rfb::where('id', $rfbId)
                        ->update(['status' => 4]);
                }
            }
        }

        $sell->status = 3;
        $sell->save();

        return response()->json([
            'message' => 'Sell details cancelled successfully'
        ]);
    }
    public function selfSellShowData($id)
    {
        $sell = SellWalletHistory::with('member')->find($id);

        if (!$sell) {
            return response()->json([
                'message' => 'Sell request not found'
            ], 404);
        }

        return response()->json([
            'sell_id' => $sell->sell_id,
            'show_wallet_balance' => $sell->show_wallet_balance,
            'payment_method' => $sell->payment_method,
            'mobile_number' => $sell->mobile_number,
            'qr_image'=>$sell->qr_image,
            'qr_image_url' => $sell->qr_image ? asset('public/'.$sell->qr_image) : null,
            'payment_details'=>$sell->payment_details,
            'status' => $sell->status,
            'created_at' => optional($sell->created_at)->format('d M Y h:i A'),
            'member_name' => $sell->member->name ?? 'Unknown Member',
        ]);
    }









    public function sendRequestForBuy()
    {    
        return view('member.smartwallet.sendRequestForBuy');
    }
    public function fetchSellerData(Request $request,$id=null)
    {
        $sellerWithSellId = [];
        $memberMemberID = session('member_memberID');
        if(!$id){
            $id=$request->input('edit_rfb_id');
        }
        if($id){
            $rfb = Rfb::findOrFail($id);
            $sellerWithSellId = RfbSeller::where('rfb_id', $rfb->id)->where('status', 1)
                ->pluck('seller_member_id')
                ->toArray();

            $wallet_balance = $rfb->amount;
            $self_member_id = $rfb->member_id; 
        }
        else{
            $wallet_balance = $request->input('wallet_balance');
            $self_member_id = ManageReport::where('memberID', $memberMemberID)->value('member_id');
        }      
      
        
        $countryname = MemberDetail::where('memberID', $memberMemberID)
            ->value('countryname');

        $sellers = SellWalletHistory::whereRaw(
                '(show_wallet_balance - total_sell_wallet_balance) >= ?',
                [$wallet_balance]
            )
            ->when($request->payment_method, function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            })
            ->where('status', 1)
            ->where('member_id', '!=', $self_member_id)
            ->whereHas('memberDetail', function ($q) use ($countryname) {
                $q->where('countryname', $countryname);
            })
            ->with('member', 'memberDetail')
            ->latest();
        if (!$request->ajax()) {
            return view('member.smartwallet.sender');
        }

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $sellers->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();
        $data = collect($records)->map(function ($row,$index)use ($sellerWithSellId) {
            $checked = in_array($row->member_id, $sellerWithSellId) ? 'checked' : '';
            return [
                'checkbox' => '<input type="checkbox" class="row-checkbox" value="'.$row->member_id.'" '.$checked.'>',
                'show_wallet_balance' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->show_wallet_balance-$row->total_sell_wallet_balance, 2).'
                            </span>',
                'mobile_number' => $row->mobile_number?$row->mobile_number:'',
                'payment_method' => $row->payment_method == 1 ? 'UPI Transfer via QR Code' : ($row->payment_method == 2 ? 'UPI Number' : ($row->payment_method == 3 ? 'Bank to Bank Transfer' : ($row->payment_method == 4 ? 'Cash to Bank Transfer' : 'Unknown'))),
                'name' => "
                        <div style='font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;'>
                            ".ucwords(strtolower($row->member->name ?? 'Unknown Member'))."
                        </div>

                        ".(!empty($row->member->memberID) ? "
                            <div style='font-size:11px;color:#0c447c;margin-top:2px;'>
                                <span style='background:#e6f1fb;padding:1px 7px;border-radius:12px;'>
                                    {$row->member->memberID}
                                </span>
                            </div>
                        " : "")."
                    ",
                'sell_id' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">'.$row->sell_id.'</span>',

            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
            'amount' =>  $wallet_balance,
        ]);
       
    }
    public function sendRequestForBuyStore(Request $request)
    {
        $request->validate([
            'sellers' => 'required|array|min:1',
            'sellers.*' => 'distinct',
            'amount' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();

        try {

            $sellerIds = array_unique($request->sellers);
            $sellerCount = count($sellerIds);

            if ($request->edit_rfb_id) {

                $rfb = Rfb::findOrFail($request->edit_rfb_id);

                $rfb->update([
                    'amount' => $request->amount,
                    'no_of_sellers' => $sellerCount
                ]);

                RfbSeller::where('rfb_id', $rfb->id)->delete();

                $member_id = $rfb->member_id;

            } else {

                $member_id = ManageReport::where('memberID', session('member_memberID'))
                    ->value('member_id');

                if (!$member_id) {
                    return response()->json(['message' => 'Invalid session member'], 400);
                }

                $count = Rfb::where('member_id', $member_id)->count();

                $rfbUniqueId = 'RFB-' . $member_id . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

                $rfb = Rfb::create([
                    'rfb_id' => $rfbUniqueId,
                    'member_id' => $member_id,
                    'amount' => $request->amount,
                    'no_of_sellers' => $sellerCount,
                    'status' => 1
                ]);
            }

            $data = [];

            foreach ($sellerIds as $sellerId) {

                $sellHistoryId = SellWalletHistory::where('member_id', $sellerId)
                    ->where('status', 1)
                    ->whereRaw('(show_wallet_balance - total_sell_wallet_balance) >= ?', [$request->amount])
                    ->value('id');

                $data[] = [
                    'rfb_id' => $rfb->id,
                    'member_id' => $member_id,
                    'sell_history_id' => $sellHistoryId,
                    'seller_member_id' => $sellerId,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            RfbSeller::insert($data);

            DB::commit();

            return response()->json([
                'message' => $request->edit_rfb_id
                    ? 'Request updated successfully'
                    : 'Request sent successfully to ' . $sellerCount . ' sellers'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function rfbListData(Request $request)
    {
        $memberMemberID = session('member_memberID');
        $member_id = ManageReport::where('memberID', $memberMemberID)->value('member_id');

        $query = Rfb::with('sellers.seller')
            ->where('member_id', $member_id)
            ->latest();

        if (!$request->ajax()) {
            return view('member.smartwallet.sendRequestForBuy');
        }

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();

        $data = collect($records)->map(function ($row,$index) {

            return [
                'DT_RowIndex' => $index + 1,
                'rfb_id' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">'.$row->rfb_id.'</span>',
                'amount' => number_format($row->amount, 2),
                'created_at' => optional($row->created_at)->format('d M Y h:i A'),
                'no_of_sellers' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.$row->no_of_sellers.'
                            </span>',
                'status' => $this->formatStatusForSentRfb($row->status),
                'actions' => '
                                <button type="button" class="btn btn-sm btn-outline-success view-btn" title="View Sellers" data-rfb-id="'.$row->id.'">
                                    <i class="bi bi-eye"></i>
                                </button>

                                '.($row->status == 1 ? '
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-btn" title="Edit" data-rfb-id="'.$row->id.'">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                ' : '').'

                                '.($row->status == 2 ? '
                                    <button type="button" class="btn btn-sm btn-outline-warning transfer-btn" title="Transfer Money" data-rfb-id="'.$row->id.'">
                                        <i class="bi bi-cash-coin"></i>
                                    </button>
                                ' : '').'
                            '
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    private function formatStatusForSentRfb($status)
    {
        switch ($status) {
            case 1:
                return '<span class="badge bg-info">Request Sent</span>';
            case 2:
                return '<span class="badge bg-success">Request Accepted</span>';
            case 5:
                return '<span class="badge bg-primary">Payment Transfered</span>';
            case 3:
                return '<span class="badge bg-danger">Closed Request</span>';
            case 6:
                return '<span class="badge bg-secondary">Payment Received</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
    public function rfbSellerList(Request $request)
    {
        $sellers = RfbSeller::where('rfb_id', $request->rfb_id)
            ->with('seller')
            ->get();

        $data = $sellers->map(function ($row) {

            return [
                'sell_id' => $row->sellId->sell_id ?? '-',
                'name'=> "
                        <div style='font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;'>
                            ".ucwords(strtolower($row->seller->name ?? 'Unknown Member'))."
                        </div>

                        ".(!empty($row->seller_member_id) ? "
                            <div style='font-size:11px;color:#0c447c;margin-top:2px;'>
                                <span style='background:#e6f1fb;padding:1px 7px;border-radius:12px;'>
                                    {$row->seller->memberID}
                                </span>
                            </div>
                        " : "")."
                    ",
                'mobile_number' => $row->sellId->mobile_number ?? '',
                'status' => $row->status,
                'actions'=>'<button class="btn btn-sm btn-info message-btn"
                                data-sender="'.$row->member_id.'"
                                data-receiver="'.$row->seller_member_id.'"
                                data-id="'.$row->id.'">
                                <i class="bi bi-chat-dots"></i>
                            </button>'
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
    public function sellerAcceptDetails($rfbId)
    {
        try {

            $rfbSeller = RfbSeller::where('rfb_id', $rfbId)->first();

            if (!$rfbSeller) {
                return response()->json([
                    'message' => 'Data not found'
                ], 404);
            }

            $sell_history_id = $rfbSeller->sell_history_id;

            if (!$sell_history_id) {
                return response()->json([
                    'message' => 'Sell history not found'
                ], 404);
            }

            $sellWalletHistory = SellWalletHistory::find($sell_history_id);

            if (!$sellWalletHistory) {
                return response()->json([
                    'message' => 'Wallet history not found'
                ], 404);
            }

            return response()->json([
                'payment_method' => $sellWalletHistory->payment_method,
                'qr_image' => '<div><img src="'.asset('public/'.$sellWalletHistory->qr_image).'" style="width:150px;height:auto;margin-bottom:15px;border-radius:8px;"></div>',
                'payment_details' => $sellWalletHistory->payment_details ?? null,
                'amount'=>number_format(Rfb::where('id',$rfbId)->value('amount'),2),
                'transferMemberId'=>session('member_memberID'),
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function transferMoneyStore(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'rfb_id' => 'required',
                'member_id' => 'required',
                'amount' => 'required|numeric|min:1',
                'transaction_id' => 'required',
                'screenshot' => 'required|image'
            ]);

            $rfbSeller = RfbSeller::where('rfb_id',$request->rfb_id)->where('status',2)->first();

            if (!$rfbSeller) {
                return response()->json(['message' => 'Invalid request'], 404);
            }

          

            // upload file
            $file = $request->file('screenshot');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/transfer'), $fileName);

            // save transfer record
            BuySellCashMemo::create([
                'rfb_id' => $request->rfb_id,
                'sell_history_id'=>$rfbSeller->sell_history_id,
                'sender_member_id' => $rfbSeller->member_id,
                'receiver_member_id' => $rfbSeller->seller_member_id,
                'amount' => $request->amount,
                'transaction_id' => $request->transaction_id,
                'comment' => $request->note,
                'qr_file' => $fileName,
                'status' =>1
            ]);

            // update status (completed)
            $rfbSeller->status = 5;
            $rfbSeller->save();

            $rfb=Rfb::find($request->rfb_id);
            $rfb->status=5;
            $rfb->save();


            DB::commit();

            return response()->json([
                'message' => 'Transfer completed successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }










    
    






    public function receiverRequestForBuy(){
        return view('member.smartwallet.receiveRequestForBuy');
    }     
          
    public function receiveRfbListData(Request $request)
    {
        $memberMemberID = session('member_memberID');
        $member_id = ManageReport::where('memberID', $memberMemberID)->value('member_id');

        $query = RfbSeller::with(['seller', 'rfb'])
                ->where('seller_member_id', $member_id)
                ->latest();

        if (!$request->ajax()) {
            return view('member.smartwallet.receiveRequestForBuy');
        }

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();

        $data = collect($records)->map(function ($row,$index) {

            return [
                'DT_RowIndex' => $index + 1,
                'rfb_id' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">'.$row->rfb->rfb_id.'</span>',
                'amount' => number_format($row->rfb->amount, 2),
                'created_at' => optional($row->rfb->created_at)->format('d M Y h:i A'),
                'no_of_sellers' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.$row->rfb->no_of_sellers.'
                            </span>',
                'status' => $this->formatStatusForReceiveRfb($row->status),
                'actions' => '<button class="btn btn-sm btn-info message-btn"
                                data-sender="'.$row->seller_member_id.'"
                                data-receiver="'.$row->member_id.'"
                                data-id="'.$row->id.'">
                                <i class="bi bi-chat-dots"></i>
                            </button>'.
                        ($row->rfb->status == 1 && $row->status == 1
                            ? '<button type="button" class="btn btn-sm btn-outline-success accept-btn" 
                                    title="Accept" data-rfbseller-id="'.$row->id.'">
                                    <i class="bi bi-check-circle"></i>
                            </button>'
                            : ''
                        )
                        .
                        ($row->rfb->status == 5 && $row->status == 5
                            ? '<button type="button" class="btn btn-sm btn-outline-primary paymentReceive-btn" 
                                    title="Payment Received" data-rfbseller-id="'.$row->id.'">
                                    <i class="bi bi-cash"></i>
                            </button>'
                            : ''
                        )
                        .(
                            in_array($row->rfb->status, [5, 6]) && in_array($row->status, [5, 6])
                            ? '
                                <button type="button" class="btn btn-sm btn-outline-success view-payment-btn" 
                                    title="View Payment" data-rfbseller-id="'.$row->id.'">
                                    <i class="bi bi-eye"></i>
                                </button>
                            '
                            : ''
                        )
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    private function formatStatusForReceiveRfb($status)
    {
        switch ($status) {
            case 1:
                return '<span class="badge bg-info">Request Received</span>';
            case 2:
                return '<span class="badge bg-success">Request Accepted</span>';
            case 3:
                return '<span class="badge bg-danger">Closed Request</span>';
            case 4:
                return '<span class="badge bg-danger">Closed Sell by Seller</span>';
            case 5:
                return '<span class="badge bg-primary">Payment Transfered</span>';
            case 6:
                return '<span class="badge bg-secondary">Payment Received</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }

    public function acceptRequest($rfbsellerId)
    {
        DB::beginTransaction();

        try {
            $memberMemberID = session('member_memberID');

            $seller_member_id = ManageReport::where('memberID', $memberMemberID)
                ->value('member_id');

            $rfbseller = RfbSeller::where('seller_member_id', $seller_member_id)
                ->where('id', $rfbsellerId)
                ->lockForUpdate()
                ->first();

            if (!$rfbseller) {
                return response()->json(['message' => 'Request not found'], 404);
            }

            $rfb = Rfb::where('id', $rfbseller->rfb_id)
                ->lockForUpdate()
                ->first();

            if (!$rfb) {
                return response()->json(['message' => 'Main request not found'], 404);
            }

            if ($rfbseller->status != 1 || $rfb->status != 1) {
                return response()->json(['message' => 'Request already processed'], 400);
            }

            // Accept selected seller
            $rfbseller->update([
                'status' => 2
            ]);

            // Reject others
            RfbSeller::where('rfb_id', $rfbseller->rfb_id)
                ->where('id', '!=', $rfbsellerId)
                ->update(['status' => 3]);

            // Update main request
            $rfb->update([
                'status' => 2
            ]);

            $seller = ManageReport::where('member_id', $seller_member_id)->first();
            $receiver = ManageReport::where('member_id', $rfb->member_id)->first();
            $seller->smart_wallet_balance = $seller->smart_wallet_balance - $rfb->amount;
            $seller->save();
            $sellWallet = SellWalletHistory::where('member_id', $seller_member_id)->where('status', 1)->first();
            if ($sellWallet) {
                $sellWallet->total_sell_wallet_balance += $rfb->amount;
                $sellWallet->save();

                if ($sellWallet->show_wallet_balance == $sellWallet->total_sell_wallet_balance) {
                    $sellWallet->status = 2;
                    $sellWallet->save();
                }

                $remaining = $sellWallet->show_wallet_balance - $sellWallet->total_sell_wallet_balance;
                $sellers = RfbSeller::where('seller_member_id', $seller_member_id)->whereIn('status', [1])->get();
                foreach ($sellers as $seller) {
                    $rfb = Rfb::find($seller->rfb_id);
                    if (!$rfb) continue;
                    if ($rfb->amount > $remaining) {
                        $seller->status = 4;
                        $seller->save();
                        continue;
                    }                    
                }
            }
            Transaction::create([
                'member_id'    => $seller->memberID,
                'added_by_id'  => $seller_member_id,
                'amount'       => $rfb->amount,
                'debit'        => $rfb->amount,
                'credit'       => 0,
                'action'       => 'Requested Smart Wallet Balance Sent to '.($receiver->name.($receiver->memberID ? " ({$receiver->memberID})" : '')),
                'type'         => 'Debit',
                'status'       => 1,
                'created_at'   => now(),
            ]);
            do {
                $transactionId = 'LWB-' . $rfb->member_id . '-' . now()->format('YmdHis') . '-' . rand(100,999);
            } while (LockWalletBalance::where('transaction_id', $transactionId)->exists());

            LockWalletBalance::create([
                'transaction_id' => $transactionId,

                'member_id'    => $rfb->member_id,
                'added_by_id'  => $seller_member_id,
                'amount'       => $rfb->amount,
                'debit'        => $rfb->amount,
                'credit'       => 0,
                'action'       => 'Requested Smart Wallet Balance Transfer By ' . $seller->name.($seller->memberID ? " ({$seller->memberID})" : ''),
                'type'         => 'Credit',
                'status'       => 1,
                'created_at'   => now(),
            ]);


            DB::commit();
            $lockedWalletBalance = LockWalletBalance::where('member_id', $seller_member_id)->where('status', 1)->sum('amount');
            return response()->json([
                'message' => 'Request accepted successfully',
                'selfwalletBalance' => number_format($seller->smart_wallet_balance, 2),
                'lockedWalletBalance' => number_format($lockedWalletBalance, 2)
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // helpful during debugging
            ], 500);
        }
    }
    public function paymentReceiveConfirmation(Request $request)
    {
        $rfbSeller = RfbSeller::find($request->rfb_seller_id);        
        $rfb=Rfb::where('id',$rfbSeller->rfb_id)->where('status',5)->first();
        if (!$rfbSeller || !$rfb) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ]);
        }
        $lockWallet = LockWalletBalance::where('member_id', $rfbSeller->member_id)
        ->where('added_by_id', $rfbSeller->seller_member_id)
        ->where('amount', $rfb->amount)
        ->first();

        if ($lockWallet) {
            $lockWallet->delete();
            Transaction::create([
                'member_id'    => ManageReport::where('member_id', $rfbSeller->member_id)->value('memberID'),
                'added_by_id'  => $rfbSeller->seller_member_id,
                'amount'       => $rfb->amount,
                'debit'        => 0,
                'credit'       => $rfb->amount,
                'action'       => 'Requested Smart Wallet Balance Sent By ' . ($rfbSeller->seller->name ?? 'Unknown Seller') . ' ('.($rfbSeller->seller->memberID ?? 'N/A').')',
                'type'         => 'Credit',
                'status'       => 1,
                'created_at'   => now(),
            ]);
        }
        $receiver = ManageReport::where('member_id', $rfbSeller->member_id)->first();
        $receiver->smart_wallet_balance = $receiver->smart_wallet_balance + $rfb->amount;
        $receiver->save();
        

        $rfb->update([
            'status' => 6
        ]);
        $rfbSeller->update([
            'status' => 6
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment received successfully'
        ]);
    }
    public function paymentDetails(Request $request)
    {
        $RfbSeller = RfbSeller::with('rfb')
            ->where('id', $request->rfb_seller_id)
            ->first();
        $payment_details=BuySellCashMemo::where('rfb_id',$RfbSeller->rfb_id)
                        ->where('sell_history_id',$RfbSeller->sell_history_id)->first();

        if (!$payment_details) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'payment_details' => $payment_details,
            'qr'=>'<img src="'.asset('public/uploads/transfer/'.$payment_details->qr_file).'" style="width:150px;height:auto;margin-bottom:15px;border-radius:8px;">'
        ]);
    }
    
}
