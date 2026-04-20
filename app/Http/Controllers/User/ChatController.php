<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ManageReport;

class ChatController extends Controller
{
    public function loadChatname(Request $request)
    {
        $sender = $request->sender;
        $receiver = $request->receiver;

        $senderUser = ManageReport::where('memberId', $sender)->first();
        $receiverUser = ManageReport::where('memberId', $receiver)->first();

        $senderName = $senderUser ? ucwords(strtolower($senderUser->name)) : 'Unknown';
        $receiverName = $receiverUser ? ucwords(strtolower($receiverUser->name)) : 'Unknown';

        $sessionId = session('member_memberID');

        $chatUserName = ($sessionId == $sender) ? $receiverName : $senderName;

        return response()->json([
            'success' => true,
            'chatUserName' => $chatUserName,
        ]);
    }
    public function loadChatHistory(Request $request)
    {
        $sender = $request->sender;
        $receiver = $request->receiver;

        $sessionId = (string) session('member_memberID');

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

            if ((string)$msg->sender_member_id === $sessionId) {

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
