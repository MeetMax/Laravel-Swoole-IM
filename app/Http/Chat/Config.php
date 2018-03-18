<?php
/**
 * Created by PhpStorm.
 * User: meetmax
 * Date: 2018/2/12
 * Time: 下午2:56
 */

namespace App\Http\Chat;


class Config implements \ArrayAccess
{
    private $config = [];
    private $path;

    public function __construct()
    {
        $this->path = __DIR__ . '/../../../config/';
    }

    public static function instance()
    {
        return new Config();
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        if(empty($this->config[$offset])){
            $this->config[$offset] = require $this->path.$offset.'.php';
        }
        return $this->config[$offset];
    }
    public function offsetSet($offset, $value)
    {

    }
    public function offsetUnset($offset){

    }
}