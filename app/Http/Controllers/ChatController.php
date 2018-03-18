<?php

namespace App\Http\Controllers;


use App\Group;
use App\GroupRelation;
use App\User;
use Illuminate\Support\Facades\Auth;



class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['uid'] = Auth::user()->id;
        $data['list'] = $this->getList();
        return view('chat.index',$data);
    }

    public function changeStatus()
    {
        $user = Auth::user();
        $user->is_online = 2;
        if(!$user->save()){
            $this->rs['code'] = -1;
            $this->rs['msg'] = 'fail';
        }
        return $this->json($this->rs);
    }

    /**
     * 初始化列表
     * @return string
     */
    public function getList()
    {
        $user = Auth::user();
        $this->rs['data']['mine'] = User::instance()->getMine($user);
        $this->rs['data']['friend'] = [
            [
                'groupname' => '在线小伙伴',
                'id' => 1,
                'online' => 2,
                'list' => User::instance()->getOnLine($user->id)
            ],
            [
                'groupname' => '离线小伙伴',
                'id' => 2,
                'online' => 2,
                'list' => User::instance()->getOnLine($user->id,1)
            ],
        ];
        $group_ids = GroupRelation::instance()->getGroupId($user->id);
        $this->rs['data']['group'] = Group::instance()->getGroup($group_ids);

        return $this->json($this->rs);
    }


}