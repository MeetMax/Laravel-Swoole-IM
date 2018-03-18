<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/17
 * Time: 下午8:32
 */

namespace App\Http\Chat;

use App\Session;
use App\ChatRecord;
use App\ChatRecordSession;
use Swoole\WebSocket\Server;

class BaseChat
{
    // 聊天类型 chat_type
    const PRIVATE_CHAT = 1; // 私聊
    const GROUP_CHAT = 2; // 群聊
    const ONLINE_NOTICE = 3; // 上线通知
    const MSG_SYNC = 4; // 消息同步


    // 在线状态
    const OFFLINE = 1; // 离线
    const ONLINE = 2; // 在线

    // 消息类型msg_type
    const NORMAL = 1;
    const NOTICE = 2;

    //群id
    const GROUP_ID = 1;

    // 机器人uid
    const ROBOT_UID = 4;

    // timeLine
    const TIME_LINE = 'time-line';

    public static function instance()
    {
        return new BaseChat();
    }

    /**
     * 消息格式
     * @param $msg
     * @param $fd
     * @param $msg_type
     * @return string
     */
    public function msgFmt($data)
    {
        $id = $data['chat_type'] == self::PRIVATE_CHAT ? $data['uid'] : $data['relation_id'];
        $data = [
            'id' => $id,
            'content' => htmlspecialchars($data['content']),
            'username' => $data['username'],
            'chat_type' => $data['chat_type'],
            'avatar' => $data['avatar'],
            'msg_type' => $data['msg_type'],
            'datetime' => date('Y-m-d H:i:s'),
        ];
        return json_encode($data);
    }

    /**
     * 接受到的格式是否正确
     * @param $data
     * @return bool
     */
    public function recvMsgFmt($data)
    {
        if(!is_array($data)){
            $data = json_decode($data,true);
        }
        $search = ['uid','username','relation_id','chat_type','content','avatar','msg_type'];
        foreach ($search as $v){
            if(!array_key_exists($v,$data)){
                return false;
            }
        }
        return true;
    }

    /**
     * 获取聊天类型
     * @param $type
     * @return mixed|string
     */
    public function getChatType($type)
    {
        do{
            $rs = '';
            if(!in_array($type,['friend', 'group'])) break;
            $arr = [
                'friend' => self::PRIVATE_CHAT,
                'group' => self::GROUP_CHAT
            ];
            $rs = $arr[$type];
        }while(false);

        return $rs;
    }

    /**
     * 存储消息
     * @param $data
     */
    public function storageChatRecord($data)
    {
        do{
            if(!$this->recvMsgFmt($data)) break;
            $session = Session::instance()->saveRecord($data['uid'],$data['relation_id'],$data['chat_type']);
            if($data['chat_type'] == BaseChat::GROUP_CHAT){
                $session[0] = $data['relation_id'];
            }
            $chat_record = ChatRecord::instance()->saveRecord($data['content'],$data['uid']);
            ChatRecordSession::instance()->saveRecord($session,$chat_record->id);

        }while(false);
    }

    /**
     * 推送消息
     * @param Server $server
     * @param $data
     * @param $uids
     */
    public function pushMsg(Server $server,$data,$uids)
    {
        do{
            if(empty($uids)) break;
            $this->storageChatRecord($data);
            $msgFmt = $this->msgFmt($data);
            var_dump($msgFmt);
            foreach ($uids as $uid)
            {
                if($uid == $data['uid']) continue;
                if(!(($storage = Storage::instance()->get($uid)) && $server->exist($storage['fd']))){
                    if(in_array($data['chat_type'],[self::PRIVATE_CHAT,self::GROUP_CHAT])){
                        $time_line = $this->getTimeLine($uid);
                        Storage::instance()->lPush($time_line,$msgFmt);
                    }
                    continue;
                }
                $server->push($storage['fd'],$msgFmt);
            }
        }while(false);
    }

    /**
     * 获取time-line
     * @param $uid
     * @return string
     */
    public function getTimeLine($uid)
    {
        return self::TIME_LINE.$uid;
    }
}