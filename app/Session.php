<?php

namespace App;

use App\Http\Chat\BaseChat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Session extends Model
{

    protected $fillable = [
        'uid','relation_id','chat_type','status','created_at','updated_at'
    ];

    public static function instance()
    {
        return new Session();
    }

    public function saveRecord($uid,$relation_id,$chat_type)
    {
        do{
            $session[0] = self::firstOrCreate([
                'uid' => $uid,
                'relation_id' => $relation_id,
                'chat_type' => $chat_type
            ])->id;
            if($chat_type == BaseChat::GROUP_CHAT) break;
            $session[1] = self::firstOrCreate([
                'uid' => $relation_id,
                'relation_id' => $uid,
                'chat_type' => $chat_type,
            ])->id;
        }while(false);

        return $session;
    }

    /**
     * 获取session_id
     * @param $uid
     * @param $relation_id
     * @param $chat_type
     * @return int|mixed
     */
    public function getSessionId($uid, $relation_id, $chat_type)
    {
        do{
            if($chat_type == BaseChat::GROUP_CHAT){
                $session_id = $relation_id;
                break;
            }
            try{
                $session = self::where('uid',$uid)
                    ->where('relation_id',$relation_id)
                    ->where('chat_type',$chat_type)
                    ->select(['id'])
                    ->firstOrFail();
                $session_id = $session->id;
            }catch (ModelNotFoundException $e){
                $session_id = 0;
            }
        }while(false);

        return $session_id;

    }
}
