<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/18
 * Time: 下午8:40
 */

namespace App\Http\Chat;

use App\User;
use Swoole\WebSocket\Server;


class Robot extends BaseChat
{
   private $api_key = 'c1e41f034a0643c0b7825874bcda0594';
   private $api_address = 'http://www.tuling123.com/openapi/api';

   public static function instance()
   {
       return new Robot();
   }

    /**
     * 机器人请求api
     * @param $uid
     * @param $content
     * @return mixed
     */
   public function postApi($uid,$content)
   {
       $post_data = [
           'key' => $this->api_key,
           'info' => $content,
           'userid' => $uid,
       ];
       $ch  = curl_init();
       curl_setopt($ch,CURLOPT_HEADER,0);
       $header = ['Content-Type: application/json'];
       curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
       curl_setopt($ch,CURLOPT_URL,$this->api_address);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
       $rs = curl_exec($ch);

       return json_decode($rs,true);
   }

    /**
     * 机器人回复的数据
     * @param $data
     * @return array
     */
   public function replyData($data)
   {
       do{
           $reply_data = [];
           if(!$this->recvMsgFmt($data)) break;
           $robot_res = Robot::instance()->postApi($data['relation_id'],$data['content']);
           $user = User::find(self::ROBOT_UID);
           $user_info = User::instance()->getMine($user);
           if($data['chat_type'] == self::PRIVATE_CHAT){
               $data['relation_id'] = $data['uid'];
           }
           $data['uid'] = self::ROBOT_UID;
           $data['content'] = $robot_res['text'];
           $reply_data = array_merge($data,$user_info);
       }while(false);

       return $reply_data;
   }

    /**
     * 推送消息
     * @param Server $server
     * @param $uids
     * @param $data
     */
   public function pushRobotMsg(Server $server,$data,$uids)
   {
       do{
           if($data['msg_type'] == self::NOTICE) break;
           $reply_data = $this->replyData($data);
           $this->pushMsg($server,$reply_data,$uids);
       }while(false);

   }
}

