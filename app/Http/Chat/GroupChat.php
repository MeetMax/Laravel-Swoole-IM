<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/17
 * Time: 下午8:34
 */

namespace App\Http\Chat;

use App\GroupRelation;
use Swoole\WebSocket\Server;

class GroupChat extends BaseChat implements ChatInterface
{


    public function afterMessage(Server $server,$fd,$data)
    {
        $this->pushGroup($server,$data);
    }

    /**
     * 群发
     * @param Server $server
     * @param $msg
     * @param $fd
     * @param int $type
     */
    public function pushGroup(Server $server,$data)
    {
        do{
            $uids = GroupRelation::instance()->getGroupMembersUid($data['relation_id']);
            $this->pushMsg($server,$data,$uids);
            Robot::instance()->pushRobotMsg($server,$data,$uids);
        }while(false);

    }
}