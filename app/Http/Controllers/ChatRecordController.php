<?php

namespace App\Http\Controllers;

use App\ChatRecordSession;
use Illuminate\Http\Request;
use App\Http\Chat\BaseChat;
use App\Session;
use App\ChatRecord;
use Illuminate\Support\Facades\Auth;


class ChatRecordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $relation_id = $request->input('id');
        $type = $request->input('type');
        $chat_type = BaseChat::instance()->getChatType($type);
        $session_id = Session::instance()->getSessionId(Auth::id(),$relation_id,$chat_type);
        $chat_record_ids = ChatRecordSession::instance()->getChatRecordId($session_id);
        $chat_record = ChatRecord::instance()->getChatRecord($chat_record_ids);
        $this->rs['data'] = $chat_record;

        return $this->json($this->rs);

    }

    public function view()
    {
        return view('chat-record.view');
    }
}
