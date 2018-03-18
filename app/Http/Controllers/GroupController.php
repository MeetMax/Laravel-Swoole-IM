<?php

namespace App\Http\Controllers;

use Auth;
use App\GroupRelation;
use App\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 获取群成员
     * @param User $user
     * @param GroupRelation $gr
     * @param Request $request
     * @return string
     */
    public function getMembers(User $user,GroupRelation $gr,Request $request)
    {
        $user_mine = Auth::user();
        do{
            $group_id = $request->input('id');
            $this->rs['data'] = [
                'owner' => User::instance()->getMine($user_mine),
                'list' => [],
                'members' => 0
            ];
            if(empty($group_id)){
                $this->rs['code'] = -1;
                $this->rs['msg'] = '参数错误';
                break;
            }
            $uids = $gr->getGroupMembersUid($group_id);
            if(empty($uids)){
                $this->rs['code'] = -1;
                $this->rs['msg'] = '群不存在';
                break;
            }
            $members = $user->getMembers($uids);
            if(empty($members)){
                $this->rs['code'] = -1;
                $this->rs['msg'] = '群成员不存在';
                break;
            }
            $this->rs['data']['list'] = $members;
            $this->rs['data']['members'] = count($members);
        }while(false);

        return $this->json($this->rs);
    }
}
