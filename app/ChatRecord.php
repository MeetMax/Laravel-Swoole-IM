<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRecord extends Model
{

    protected $fillable = [
        'uid','content','status','created_at','updated_at'
    ];

    public static function instance()
    {
        return new ChatRecord();
    }

    public function users()
    {
        return $this->hasOne('App\User','id','uid');
    }

    /**
     * 保存聊天记录
     * @param $content
     * @param $uid
     * @return $this|Model
     */
    public function saveRecord($content,$uid)
    {
        return self::instance()->create([
            'uid' => $uid,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 获取聊天记录
     * @param $session_id
     * @return array
     */
    public function getChatRecord($ids)
    {
        $chat_records = self::whereIn('id',$ids)
            ->with(['users' => function($query){
                $query->select(['id','nick_name as username','header as avatar']);
            }])
            ->where('status',1)
            ->select(['uid','content','created_at as timestamp'])
            ->get()->toArray();
        foreach ($chat_records as $k => $v){
            $chat_records[$k]['id'] = $v['uid'];
            unset($chat_records[$k]['uid']);
            $chat_records[$k]['timestamp'] = strtotime($v['timestamp']) * 1000;
            $chat_records[$k]['username'] = $v['users']['username'];
            $chat_records[$k]['avatar'] = $v['users']['avatar'];
            unset($chat_records[$k]['users']);
        }
        return $chat_records;
    }
}
