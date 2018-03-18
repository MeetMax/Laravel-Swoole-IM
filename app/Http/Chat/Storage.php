<?php

namespace App\Http\Chat;

class Storage
{
    private $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1',6379);
    }

    public static function instance()
    {
        return new Storage();
    }

    /**
     * 获取val
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
       return json_decode($this->redis->get($key),true);
    }

    /**
     * 设置key,val
     * @param $key
     * @param $val
     * @return bool
     */
    public function set($key,$val)
    {
        if(is_array($val)){
            $val = json_encode($val);
        }
        return $this->redis->set($key,$val);
    }

    /**
     * 删除key
     * @param $key
     * @return int
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }


    /**
     * 进入队列
     * @param $list
     * @param $val
     */
    public function lPush($list,$val)
    {
        if(is_array($val)){
            $val = json_encode($val);
        }
        return $this->redis->lPush($list,$val);
    }

    /**
     * 出队
     * @param $list
     * @return mixed
     */
    public function rPop($list)
    {
        return $this->redis->rPop($list);
    }
}