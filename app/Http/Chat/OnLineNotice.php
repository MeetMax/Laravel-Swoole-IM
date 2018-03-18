<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/17
 * Time: 下午8:34
 */

namespace App\Http\Chat;

use App\User;
use App\GroupRelation;
use App\Group;
use Swoole\WebSocket\Server;
class OnLineNotice extends BaseChat implements ChatInterface
{
    public function afterMessage(Server $server,$fd,$data)
    {
        $this->onLine($server,$fd,$data['uid']);
        $group_id = Group::instance()->getGroupId();
        GroupRelation::instance()->joinGroup($group_id,$data['uid']);
    }

    /**
     * 设置user_info
     * @param $fd
     * @param $uid
     * @param $chat_type
     */
    public function onLine(Server $server,$fd,$uid)
    {
        do{
            $bind = $server->bind($fd,(int)$uid);
            if(!$bind) break;
            $user_info = ['fd' => $fd];
            User::instance()->setOnLine($uid,self::ONLINE);
            Storage::instance()->set($uid,$user_info);
            echo "连接成功 \n";
        }while(false);
    }
}