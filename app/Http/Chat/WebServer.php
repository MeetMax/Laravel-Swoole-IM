<?php
namespace App\Http\Chat;
use App\User;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\WebSocket\Server;


class WebServer extends BaseChat {
    private $server;
    private $config;
    private $uid;





    //发送类型
    const ALL = 1;
    const ONE = 2;
    const GROUP = 3;

    const PARAMS = 'swoole-config';

    public function __construct()
    {
        $this->config = Config::instance();
    }

    public static function instance()
    {
        return new WebServer();
    }


    public function run()
    {
        $this->server = new Server($this->config[self::PARAMS]['ip'],$this->config[self::PARAMS]['port']);
        $this->server->set($this->config[self::PARAMS]['set']);
        $this->server->on('open',[$this,'open']);
        $this->server->on('message',[$this,'message']);
        $this->server->on('close',[$this,'close']);
        $this->server->start();
    }

    /**
     *
     * @param Server $server
     * @param $request
     */
    public function open(Server $server,$request)
    {
    }

    /**
     * 发送消息事件
     * @param Server $server
     * @param $frame
     */
    public function message(Server $server,$frame)
    {
        do{
            if(!$this->recvMsgFmt($frame->data)) break;
            $data = json_decode($frame->data,true);
            if(!isset($data['uid']) || !isset($data['chat_type'])) break;
            ChatFactory::create($data['chat_type'])->afterMessage($server,$frame->fd,$data);

        } while(false);

    }

    /**
     * 关闭事件
     * @param Server $server
     * @param $fd
     */
    public function close(Server $server,$fd)
    {
        do{
            $con_info = $server->connection_info($fd);
            if(!array_key_exists('uid',$con_info)) break;
            $uid = $con_info['uid'];
            User::instance()->setOnLine($uid,self::OFFLINE);
            Storage::instance()->del($uid);
        }while(false);

    }


}

