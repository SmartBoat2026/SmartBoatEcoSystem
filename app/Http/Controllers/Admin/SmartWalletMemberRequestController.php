<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\ManageReport;
use App\Models\SmartWalletCompanyPayment;
use Illuminate\Http\Request;

class SmartWalletMemberRequestController extends Controller
{
    public function memberRequest()
    {    
        return view('admin.smartwallet_memberRequest');
    }  
    
    public function statusUpdate(Request $request, $id)
    {
        try {

            $request->validate([
                'status' => 'required|in:1,2,3'
            ]);

            $row = SmartWalletCompanyPayment::find($id);

            if (!$row) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 404);
            }

            $row->status = $request->status;
            $row->save();

            if ($request->status == 2) {

                $amount = $row->amount;
                $member_id = $row->sender_member_id;
                $admin_member_id = $row->admin_member_id;

                $sender = ManageReport::where('member_id', $member_id)->first();

                if ($sender) {

                    $sender->smart_wallet_balance += $amount;
                    $sender->save();

                    Transaction::create([
                        'member_id'   => $member_id,
                        'added_by_id' => $admin_member_id,
                        'type'        => 'credit',
                        'amount'      => $amount,
                        'action'      => 'Company Payment Approved',
                        'status'      => 1,
                        'created_at'  => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status'  => $row->status
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function listData(Request $request)
    {
        $adminId = session('admin_id');

        $query = SmartWalletCompanyPayment::with('sender')
            ->where('admin_member_id', $adminId)
            ->latest();

        // AJAX check
        if (!$request->ajax()) {
            return view('member.smartwallet.companyPayment');
        }

        $perPage = $request->length ?? 10;
        $page = intval(($request->start ?? 0) / $perPage) + 1;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $records = $paginated->items();

        $data = collect($records)->map(function ($row,$index) {

            return [
                'DT_RowIndex' => $index + 1,

                'member_id' => $row->sender->memberID ?? 'N/A',

                'member_info' => '
                    <div class="fw-semibold">'.ucwords(strtolower($row->sender->name)).'</div>
                    <div class="text-muted small">'.$row->sender->email.'</div>
                    <div class="text-muted small">'.$row->sender->phone.'</div>
                ',

                'amount' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->amount, 2).'
                            </span>',

                'qr_file' => $row->qr_file
                    ? '<a href="'.asset('public/uploads/company_payment/'.$row->qr_file).'" target="_blank" class="btn btn-sm btn-light">
                            <i class="bi '.(in_array(strtolower(pathinfo($row->qr_file, PATHINFO_EXTENSION)), ['jpg','jpeg','png'])
                            ? 'bi-image'
                            : 'bi-file-earmark-pdf').'"></i>
                    </a>'
                    : '<span class="text-muted">N/A</span>',

                'created_at' => optional($row->created_at)->format('d M Y h:i A'),

                'status' => $this->formatStatus($row->status,$row->id),

                'action' => $this->formatActions($row),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    public function formatStatus($status, $id)
    {
        $map = [
            1 => ['Pending', 'bg-warning'],
            2 => ['Accepted', 'bg-success'],
            3 => ['Rejected', 'bg-danger'],
        ];

        $s = $map[$status] ?? ['Unknown', 'bg-dark'];

        if ($status == 2 || $status == 3) {
            return '<span class="badge '.$s[1].'">'.$s[0].'</span>';
        }

        return '
            <div class="btn-group btn-group-sm">
                <span class="badge '.$s[1].'">'.$s[0].'</span>
                <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">⚙</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item change-status status-btn text-success" data-id="'.$id.'" data-status="2">Accept</a></li>
                    <li><a class="dropdown-item change-status status-btn text-danger" data-id="'.$id.'" data-status="3">Reject</a></li>
                </ul>
            </div>
        ';
    }
    private function formatActions($row)
    {
        return '<button class="btn btn-sm btn-info message-btn"
                    data-sender="'.$row->admin_member_id.'"
                    data-receiver="'.$row->sender_member_id.'"
                    data-id="'.$row->id.'">
                    <i class="bi bi-chat-dots"></i>
                </button>';
    }
}
