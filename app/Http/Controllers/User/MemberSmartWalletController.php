<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;  

use App\Models\ManageReport;
use App\Models\SmartWalletRequest;

use Illuminate\Http\Request;

class MemberSmartWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function sender()
    {        
        $senderMemberId = session('member_memberID');

        $senders = SmartWalletRequest::where('sender_member_id', $senderMemberId)
            ->latest()
            ->get();
        return view('member.smartwallet.sender', compact('senders'));
    }
    public function getMembers(Request $request)
    {
        $loggedInMemberId = session('member_memberID');

        $query = ManageReport::where('smart_wallet_balance', '>', 0)
            ->where('memberID', '!=', $loggedInMemberId);
        $requestMember_id = trim($request->member_id);
        if ($requestMember_id) {
            $query->where(function ($q) use ($requestMember_id) {
                $q->where('memberID', 'like', '%' . $requestMember_id . '%')
                ->orWhere('name', 'like', '%' . $requestMember_id . '%');
            });
        }

        $members = $query->select('memberID', 'name', 'phone', 'smart_wallet_balance')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'results' => $members
        ]);
    }
    public function receiver()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id'       => 'required|exists:manage_reports,memberID',
            'request_balance' => 'required|numeric|min:1',
            'wallet_balance'  => 'required|numeric|min:0',
        ]);

        $senderMemberId = session('member_memberID');
        $receiverMemberId = $request->member_id;

        if ($senderMemberId == $receiverMemberId) {
            return back()->withErrors([
                'member_id' => 'You cannot send request to yourself.'
            ]);
        }

        $walletBalance = floatval($request->wallet_balance);
        $requestAmount = floatval($request->request_balance);

        if ($requestAmount > $walletBalance) {
            return back()->withErrors([
                'request_balance' => 'Request amount exceeds wallet balance.'
            ]);
        }

        SmartWalletRequest::create([
            'sender_member_id'   => $senderMemberId,
            'receiver_member_id' => $receiverMemberId,
            'wallet_balance'     => $walletBalance,
            'request_balance'    => $requestAmount,
            'status'             => 1, // pending
        ]);
        $receiver = ManageReport::where('memberID', $receiverMemberId)->first();

        $receiverName = $receiver->name ?? 'Member';
        $receiverPhone = $receiver->phone ?? 'N/A';

        $message = "Request sent successfully to <b>{$receiverName}</b> (<b>{$receiverMemberId}</b>) for ₹"
        . number_format($requestAmount, 2)
        . " now you can contact them at <b>{$receiverPhone}</b>.";

        
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
