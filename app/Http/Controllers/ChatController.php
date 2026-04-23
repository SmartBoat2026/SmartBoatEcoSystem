<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ManageReport;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sessionId()
    {
        if (session()->has('admin_logged_in')) {
            $sessionId = session('admin_id');
            return ManageReport::where('member_id', $sessionId)->value('memberID');;
        }

        if (session()->has('member_logged_in')) {
            return session('member_memberID');
        }

        return null;
    }
    public function loadChatname(Request $request)
    {
        $sender = $request->sender;
        $receiver = $request->receiver;

        $senderUser = ManageReport::where('member_id', $sender)->first();
        $receiverUser = ManageReport::where('member_id', $receiver)->first();

        $senderName = $senderUser ? ucwords(strtolower($senderUser->name)) : 'Unknown';
        $senderMemberID = $senderUser ? strtoupper($senderUser->memberID) : 'Unknown';
        $receiverName = $receiverUser ? ucwords(strtolower($receiverUser->name)) : 'Unknown';
        $receiverMemberID = $receiverUser ? strtoupper($receiverUser->memberID) : 'Unknown';

        $sessionId = $this->sessionId();
        $session_member_id = ManageReport::where('memberID', $sessionId)->value('member_id');
        $chatUserName = ($session_member_id == $sender) ? $receiverName : $senderName;
        $chatUserMemberID = ($session_member_id == $sender) ? $receiverMemberID : $senderMemberID;

        return response()->json([
            'success' => true,
            'chatUserName' => $chatUserName,
            'chatUserMemberID' => $chatUserMemberID
        ]);
    }
    public function loadChatHistory(Request $request)
    {
        $sender = $request->sender;
        $receiver = $request->receiver;

        $sessionId = (string) $this->sessionId();
        $session_member_id = ManageReport::where('memberID', $sessionId)->value('member_id');

        $messages = Chat::where(function ($q) use ($sender, $receiver) {
                $q->where('sender_member_id', $sender)
                ->where('receiver_member_id', $receiver);
            })
            ->orWhere(function ($q) use ($sender, $receiver) {
                $q->where('sender_member_id', $receiver)
                ->where('receiver_member_id', $sender);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $html = '';
        $lastDate = null;

        foreach ($messages as $msg) {

            $msgDate = date('d M Y', strtotime($msg->created_at));

            if ($lastDate != $msgDate) {
                $html .= '<div style="text-align:center;margin:10px 0;color:#777;font-size:12px;">
                            '.$msgDate.'
                        </div>';
                $lastDate = $msgDate;
            }

            $time = date('h:i A', strtotime($msg->created_at));

            if ((string)$msg->sender_member_id === (string)$session_member_id){

                $html .= '
                    <div style="display:flex;justify-content:flex-end;margin-bottom:5px;">
                        <div style="background:#dcf8c6;padding:8px 12px;border-radius:10px;max-width:70%;">
                            <div>'.$msg->message.'</div>
                            <div style="font-size:10px;text-align:right;color:#666;margin-top:3px;">
                                '.$time.'
                            </div>
                        </div>
                    </div>
                ';

            } else {

                $html .= '
                    <div style="display:flex;margin-bottom:5px;">
                        <div style="background:#fff;padding:8px 12px;border-radius:10px;max-width:70%;">
                            <div>'.$msg->message.'
                            </div>
                            <div style="font-size:10px;text-align:left;color:#666;margin-top:3px;">
                                '.$time.'
                            </div>
                        </div>
                    </div>
                ';
            }
        }

        return response()->json([
            'html' => $html
        ]);
    }
    public function listChats(Request $request)
    {
        $sessionId = (string) $this->sessionId();

        $session_member_id = ManageReport::where('memberID', $sessionId)
            ->value('member_id');

        $search = $request->search;

        $chats = Chat::where(function ($q) use ($session_member_id) {
                $q->where('sender_member_id', $session_member_id)
                ->orWhere('receiver_member_id', $session_member_id);
            })
            ->when($search, function ($q) use ($search) {

                $q->where(function ($qq) use ($search) {

                    $qq->whereHas('senderUser', function ($u) use ($search) {
                        $u->where('member_id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('receiverUser', function ($u) use ($search) {
                        $u->where('member_id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                    });

                });

            })
            ->with([
                'senderUser:member_id,name,phone',
                'receiverUser:member_id,name,phone'
            ])
            ->orderBy('id', 'desc')
            ->get();

        $chatUsers = $chats->map(function ($chat) use ($session_member_id) {

            $otherUser = $chat->sender_member_id == $session_member_id
                ? $chat->receiverUser
                : $chat->senderUser;

            if (!$otherUser) return null;
            if($session_member_id == $chat->receiver_member_id)
                {
                    $sender_member_id = $chat->receiver_member_id;
                    $receiver_member_id = $chat->sender_member_id;
                }
            else{
                    $sender_member_id = $chat->sender_member_id;
                    $receiver_member_id = $chat->receiver_member_id;
                }
            return [
                'member_id' => $otherUser->member_id,
                'name' => $otherUser->name,
                'phone' => $otherUser->phone ?? '',
                'last_message' => $chat->message ?? '',
                'time' => $chat->created_at->format('h:i A'),
                'sender_member_id' => $sender_member_id,
                'receiver_member_id' => $receiver_member_id,
                'type' => 'chat'
            ];
        })
        ->filter()
        ->unique('member_id')
        ->values();

        $reportUsers = collect();

        if ($search) {

            $reportUsers = ManageReport::where('member_id', '!=', $session_member_id)
                ->where(function ($qq) use ($search) {
                    $qq->where('member_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
                })
                ->limit(10)
                ->get()
                ->map(function ($user) use ($session_member_id) {
                    return [
                        'member_id' => $user->member_id,
                        'name' => $user->name,
                        'phone' => $user->phone ?? '',
                        'last_message' => 'Start chatting...',
                        'time' => '',
                        'sender_member_id' => $session_member_id,
                        'receiver_member_id' => $user->member_id,
                        'type' => 'new'
                    ];
                })
                ->values();
        }

        $users = $chatUsers->concat($reportUsers)->values();

        return response()->json([
            'users' => $users
        ]);
    }
    public function sendMessage(Request $request)
    {
        $request->validate([
            'sender' => 'required',
            'receiver' => 'required',
            'message' => 'required'
        ]);

        $chat = new Chat();
        $chat->sender_member_id = $request->sender;
        $chat->receiver_member_id = $request->receiver;
        $chat->message = $request->message;
        $chat->save();

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
    }
}
