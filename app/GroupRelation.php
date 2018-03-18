<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GroupRelation extends Model
{
    protected $fillable = [
        'uid','group_id','status','created_at','updated_at'
    ];

    public static function instance()
    {
        return new GroupRelation();
    }

    /**
     * @param $group_id
     * @param $uid
     */
    public function joinGroup($group_id,$uid)
    {
        try{
            $group_relation = self::where('group_id',$group_id)
                ->where('uid',$uid)
                ->firstOrFail();
            if($group_relation->status == 2){
                $group_relation->status = 1;
                $group_relation->save();
            }
        }catch (ModelNotFoundException $e) {
            self::create([
                'uid' => $uid,
                'group_id' => $group_id
            ]);
        }

    }

    /**
     * 获取group_id
     * @param $uid
     * @return array
     */
    public function getGroupId($uid)
    {
        do{
            $rs = [];
            $group_ids = self::where('uid',$uid)
                ->where('status',1)
                ->select(['group_id'])
                ->get()->toArray();
            if(empty($group_ids)) break;
            $rs = array_column($group_ids,'group_id');

        }while(false);

        return $rs;
    }

    /**
     * 获取群成员uid
     * @param $group_id
     * @return array
     */
    public function getGroupMembersUid($group_id)
    {
        do{
            $rs = [];
            $uids = self::where('group_id',$group_id)
                ->where('status',1)
                ->select(['uid'])
                ->get()->toArray();
            if(empty($uids)) break;
            $rs = array_column($uids,'uid');

        }while(false);

        return $rs;
    }
}
