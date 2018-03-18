<?php

namespace App\Http\Controllers;

use App\Http\Chat\BaseChat;
use App\Http\Chat\Storage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 修改签名
     * @param Request $request
     * @return string
     */
    public function updateSign(Request $request)
    {
        $sign = $request->input('sign');
        $user = Auth::user();
        $user->sign = $sign;
        if(!$user->save()){
            $this->rs['code'] = -1;
            $this->rs['msg'] = 'fail';
        }

        return $this->json($this->rs);
    }

    /**
     * 关闭链接，修改状态
     * @param Request $request
     * @return string
     */
    public function closeConn(Request $request)
    {
        $uid = $request->input('uid');
        Storage::instance()->del($uid);
        User::instance()->setOnLine($uid,BaseChat::ONLINE);
        return $this->json($this->rs);
    }
}
