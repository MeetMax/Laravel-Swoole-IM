<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/17
 * Time: 下午8:34
 */

namespace App\Http\Chat;

use Swoole\WebSocket\Server;


class PrivateChat extends BaseChat implements ChatInterface
{

    public function afterMessage(Server $server,$fd,$data)
    {
       do{

           if($data['relation_id'] == self::ROBOT_UID){
                $this->storageChatRecord($data);
                $data = Robot::instance()->replyData($data);
           }
           $this->pushOne($server,$data);
       }while(false);

    }

    /**
     * 私聊
     * @param Server $server
     * @param $msg
     * @param $fd
     * @param $toFd
     */
    private function pushOne(Server $server,$data)
    {
        do{
            if(!$this->recvMsgFmt($data)) break;
            $uids[0] = $data['relation_id'];
            $this->pushMsg($server,$data,$uids);
        }while(false);

    }
}