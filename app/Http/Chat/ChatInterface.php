<?php

namespace App\Http\Chat;

use Swoole\WebSocket\Server;


 interface ChatInterface{
     public function afterMessage(Server $server,$fd,$data);
 }