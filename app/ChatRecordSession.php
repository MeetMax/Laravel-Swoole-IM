<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRecordSession extends Model
{
    protected $fillable = [
        'session_id','chat_record_id','status','created_at','updated_at'
    ];

    public static function instance()
    {
        return new ChatRecordSession();
    }

    public function saveRecord($session_ids,$chat_record_id)
    {
        foreach ($session_ids as $session_id){
            self::instance()->create([
                'session_id' => $session_id,
                'chat_record_id' => $chat_record_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * 获取聊天记录id
     * @param $session_id
     * @return array
     */
    public function getChatRecordId($session_id)
    {
        $cr_session = self::where('session_id',$session_id)->select(['chat_record_id'])->get()->toArray();
        $chat_record_ids = array_column($cr_session,'chat_record_id');
        return $chat_record_ids;
    }
}
