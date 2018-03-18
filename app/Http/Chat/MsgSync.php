<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/22
 * Time: 上午10:30
 */

namespace App\Http\Chat;


use Swoole\WebSocket\Server;

class MsgSync extends BaseChat implements ChatInterface
{
    public function afterMessage(Server $server, $fd, $data)
    {
        do{
            $time_line = $this->getTimeLine($data['uid']);
            while (1){
                if(!($msg = Storage::instance()->rPop($time_line))) break;
                $server->push($fd,$msg);
            }

        }while(false);
    }
}