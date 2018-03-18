<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/17
 * Time: 下午8:31
 */

namespace App\Http\Chat;


class ChatFactory extends BaseChat
{
    public static function create($type)
    {

        switch ($type)
        {
            case self::PRIVATE_CHAT:
                $rs = new PrivateChat();
                break;
            case self::GROUP_CHAT:
                $rs = new GroupChat();
                break;
            case self::ONLINE_NOTICE:
                $rs = new OnLineNotice();
                break;
            case self::MSG_SYNC:
                $rs = new MsgSync();
                break;
            default:
                $rs = new PrivateChat();
                break;

        }

        return $rs;
    }
}