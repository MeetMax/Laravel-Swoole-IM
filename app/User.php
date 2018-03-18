<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'nick_name', 'password', 'create_time', 'update_time', 'header'
    ];

    public static function instance()
    {
        return new User();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setOnLine($uid,$status)
    {
        if(!empty($uid)){
            $user = User::find($uid);
            $user->is_online = $status;
            $user->save();
        }

    }

    /**
     * 获取在线人列表
     * @param $uid
     * @return array
     */
    public function getOnLine($uid,$status = 2)
    {
        $list = User::where('is_online',$status)
            ->where('id','!=',$uid)
            ->select($this->commonSelect())
            ->get()->toArray();
        return $list;
    }

    /**
     * 获取个人信息
     * @param $user
     * @return array
     */
    public function getMine($user)
    {
        $mine= [
            'username' => $user->nick_name,
            'id' => $user->id,
            'sign' => $user->sign,
            'avatar' => $user->header
        ];
        return $mine;
    }

    /**
     * 获取成员信息
     * @param $uids
     * @return array
     */
    public function getMembers($uids)
    {
        $users = self::whereIn('id',$uids)
            ->select($this->commonSelect())
            ->get()->toArray();
        return $users;
    }

    /**
     * 公共select
     * @return array
     */
    private function commonSelect()
    {
        return ['id','nick_name as username','header as avatar','sign'];
    }
}
