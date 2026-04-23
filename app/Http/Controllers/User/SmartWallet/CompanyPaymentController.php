<?php

namespace App\Http\Controllers\User\SmartWallet;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManageReport;
use App\Models\SmartWalletCompanyPayment;

class CompanyPaymentController extends Controller
{
    public function companyPayment()
    {    
        return view('member.smartwallet.companyPayment');
    }    

    public function loadModelOpenData(Request $request)
    {
        try {
            $sessionMemberId = session('member_memberID');

            $admin = ManageReport::select('phone', 'email',)
                            ->where('member_id','1')
                            ->first();

        
            return response()->json([                
                'adminPh' => $admin->phone,
                'adminEmail'    => $admin->email, 
                'sessionMemberId' => $sessionMemberId,
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => 'Failed to load data'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'member_id'       => 'required|exists:manage_reports,memberID',
                'amount'          => 'required|numeric|min:1',
                'transaction_id'  => 'required',
                'qr_file'         => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $sessionMemberId = session('member_memberID');
            $self_member_id = ManageReport::where('memberID', $request->member_id)->value('member_id');
            $fileName = null;

            if ($request->hasFile('qr_file')) {
                $file = $request->file('qr_file');
                $fileName = $self_member_id.'_'.time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/company_payment'), $fileName);
            }

            
            $payment = new SmartWalletCompanyPayment();
            $payment->sender_member_id = $self_member_id;
            $payment->admin_member_id = 1;
            $payment->amount = $request->amount;
            $payment->transaction_id = $request->transaction_id;
            $payment->comment = $request->comment;
            $payment->qr_file = $fileName;
            $payment->status = 1; // pending
            $payment->save();

            

            return response()->json([
                'success' => true,
                'message' => 'Payment submitted successfully',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function listData(Request $request)
    {
        $senderMemberId = session('member_memberID');
        $sender_member_id = ManageReport::where('memberID', $senderMemberId)->value('member_id');

        $query = SmartWalletCompanyPayment::with('sender')
            ->where('sender_member_id', $sender_member_id)
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
                'date' => optional($row->created_at)->format('d M Y h:i A'),

                'amount' => '<span class="badge" style="background:#eeedfe;color:#3c3489;">
                                '.number_format($row->amount, 2).'
                            </span>',
                'transaction_id' => $row->transaction_id ?? 'N/A',
                'qr_file' => $row->qr_file 
                    ? '<a href="'.asset('public/uploads/company_payment/'.$row->qr_file).'" target="_blank">
                        <i class="bi '.(in_array(strtolower(pathinfo($row->qr_file, PATHINFO_EXTENSION)), ['jpg','jpeg','png']) 
                        ? 'bi-image' 
                        : 'bi-file-earmark-pdf').'"></i>
                    </a>' 
                    : 'N/A',
                'comment' => $row->comment ?? '-',

                'status' => $this->formatStatus($row->status),

                'actions' => $this->formatActions($row),
            ];
        });

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $paginated->total(),
            'recordsFiltered' => $paginated->total(),
            'data' => $data,
        ]);
    }
    public function formatStatus($status)
    {
        $map = [
            1 => ['Pending','bg-warning'],
            2 => ['Accepted','bg-success'],
            3 => ['Rejected','bg-danger'],
        ];

        $s = $map[$status] ?? ['Unknown','bg-dark'];

        return '<span class="badge '.$s[1].'">'.$s[0].'</span>';
    }   
    private function formatActions($row)
    {
        return '<button class="btn btn-sm btn-info message-btn"
                    data-sender="'.$row->sender_member_id.'"
                    data-receiver="'.$row->admin_member_id.'"
                    data-id="'.$row->id.'">
                    <i class="bi bi-chat-dots"></i>
                </button>';
    }
}
