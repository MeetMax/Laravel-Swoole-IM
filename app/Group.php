<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Group extends Model
{
    protected $fillable = [
        'name','cover','desc','status','created_at','updated_at'
    ];

    public static function instance()
    {
        return new Group();
    }

    public function getGroupId()
    {
        try{
            $group_id = self::firstOrFail()->id;
        }catch (ModelNotFoundException $e) {
            $group_id = self::create([
                'name' => '在线群聊',
                'cover' => 'https://dn-phphub.qbox.me/uploads/banners/ql9XtosRhTe4v8HVC3TV.jpg',
                'desc' => '一群二货的聊天室',
                'created_at' => date('Y-m-d H:i:s')
            ])->id;
        }

        return $group_id;
    }

    /**
     * @param $ids
     * @return array
     */
    public function getGroup($ids)
    {
        do{
            $rs = [];
            if(empty($ids)) break;
            $rs = self::whereIn('id',$ids)
                ->select(['id','name as groupname','cover as avatar'])
                ->get()->toArray() ?? [];

        }while(false);

        return $rs;
    }

}
