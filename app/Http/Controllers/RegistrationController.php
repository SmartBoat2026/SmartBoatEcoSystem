<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Registration;
use App\Models\Admin;

class RegistrationController extends Controller
{

    /* ── Default/Admin sponsor assigned when user has no sponsor ── */
    private const DEFAULT_SPONSOR_ID   = 'SB0783633087';
    private const DEFAULT_SPONSOR_NAME = 'HINDOL MUKHERJEE';

    public function index()
    {
        return view('admin.register');
    }

    public function sponsorLookup(Request $request)
    {
        $sponsorId = trim($request->query('sponsor_id', ''));
        if (empty($sponsorId)) return response()->json(['found' => false]);

        $member = Registration::where('memberID', $sponsorId)->first();
        if (!$member) return response()->json(['found' => false]);

        return response()->json([
            'found'      => true,
            'name'       => $member->name,
            'email'      => $member->email,
            'memberID'   => $member->memberID,
            'sponser_id' => $member->sponser_id,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|min:3|max:100',
            'email'        => 'nullable|email|max:150',
            'date_of_birth'=> 'required|string',
            'gender'       => 'required|string|in:Male,Female,Other',
            'sponsor_type' => 'required|in:no_sponsor,has_sponsor',
        ], [
            'name.required'          => 'Full name is required.',
            'name.min'               => 'Name must be at least 3 characters.',
            'email.email'            => 'Please enter a valid email address.',
            'date_of_birth.required' => 'Age group is required.',
            'gender.required'        => 'Gender is required.',
            'sponsor_type.required'  => 'Please select a sponsor option.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->sponsor_type === 'has_sponsor') {
            $sponsorId = trim($request->sponser_id ?? '');
            if (empty($sponsorId)) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['sponsor' => ['Sponsor ID is required.']],
                ], 422);
            }
            $sponsorMember = Registration::where('memberID', $sponsorId)->first();
            if (!$sponsorMember) {
                return response()->json([
                    'success' => false,
                    'errors'  => ['sponsor' => ['Sponsor ID does not exist.']],
                ], 422);
            }
            $sponsorId   = $sponsorMember->memberID;
            $sponsorName = $sponsorMember->name;
        } else {
            /* no_sponsor → always assign the fixed admin sponsor */
            $sponsorId   = self::DEFAULT_SPONSOR_ID;
            $sponsorName = self::DEFAULT_SPONSOR_NAME;
        }

        //Check referral CODE
        if($request->referral_code!=''){
            $referrralCodeVerification = Registration::where('referral_code', $request->referral_code)->first();
                if (!$sponsorMember) {
                   $referallBY = "";
                }else{
                    $referallBY = $request->referral_code;
                }
        }


        $memberID = Registration::generateMemberID();
        $password = Registration::generatePassword();
        $txnPass  = Registration::generatePassword();
        $referallCode        = $this->generateReferralCode();

        Registration::create([
            'memberID'                        => $memberID,
            'name'                            => trim($request->name),
            'phone'                           => '',
            'country_code'                    => '',
            'email'                           => trim($request->email ?? ''),
            'age'                             => trim($request->date_of_birth ?? ''),
            'gender'                          => trim($request->gender ?? ''),
            'password'                        => $password,
            'transaction_password'            => $txnPass,
            'sponser_id'                      => $sponsorId,
            'sponser_name'                    => $sponsorName,
            'joining_date'                    => now()->format('d/m/Y'),
            'smart_point'                     => 0,
            'smart_quanity'                   => '',
            'status'                          => '2',
            'amount'                          => '',
            'referral_code'                   => $referallCode,
            'referral_by'                     => $referallBY,
            'smart_wallet_balance'            => 0,
            'verification_payment_screenshot' => '',
            'payment_utr_no'                  => '',
            'verification_message'            => '',
            'created_at'                      => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->json([
            'success'              => true,
            'name'                 => trim($request->name),
            'memberID'             => $memberID,
            'password'             => $password,
            'transaction_password' => $txnPass,
            'amount'               => '',
        ]);
    }

    //REFERAL CODE GENERATER
    private function generateReferralCode($length = 8) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $result;
    }

    public function sponsorSearch(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        $results = Registration::where(function ($query) use ($q) {
                $query->where('name',        'LIKE', "%{$q}%")
                    ->orWhere('email',     'LIKE', "%{$q}%")
                    ->orWhere('memberID',  'LIKE', "%{$q}%")
                    ->orWhere('sponser_id','LIKE', "%{$q}%");
            })
            ->select('memberID', 'name', 'email', 'sponser_id')
            ->limit(8)
            ->get();

        return response()->json($results);
    }

    public function referralCodeVerification(Request $request)
    {
        $code = $request->referral_code;

        $exists = Registration::where('referral_code', $code)->exists();

        return response()->json([
            'status' => $exists
        ]);
    }

    public function paymentSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id'                       => 'required|string|exists:manage_reports,memberID',
            'payment_utr_no'                  => 'required|string|max:50',
            'amount'                          => ['required', 'string', 'max:20', 'regex:/^\d+(\.\d{1,2})?$/'],
            'verification_payment_screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'verification_message'            => 'nullable|string|max:500',
        ], [
            'member_id.exists'                         => 'Invalid member ID.',
            'payment_utr_no.required'                  => 'UTR / Transaction number is required.',
            'amount.required'                          => 'Amount paid is required.',
            'amount.regex'                             => 'Amount must be a valid number (e.g. 999 or 999.50).',
            'verification_payment_screenshot.required' => 'Payment screenshot is required.',
            'verification_payment_screenshot.image'    => 'The file must be an image.',
            'verification_payment_screenshot.mimes'    => 'Only JPG, PNG, WEBP formats are allowed.',
            'verification_payment_screenshot.max'      => 'Screenshot must not exceed 5 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // ── Store screenshot in public/storage/payment_screenshots/ ──
        $file      = $request->file('verification_payment_screenshot');
        $filename  = time() . '_' . $request->member_id . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('storage/payment_screenshots'), $filename);
        $screenshotPath = 'payment_screenshots/' . $filename;

        Registration::where('memberID', $request->member_id)
            ->update([
                'payment_utr_no'                  => trim($request->payment_utr_no),
                'amount'                          => trim($request->amount),
                'verification_payment_screenshot' => $screenshotPath,
                'verification_message'            => trim($request->verification_message ?? ''),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment proof submitted successfully.',
        ]);
    }
}
