<?php

namespace App\Services;

use App\Models\Follow;
use App\Models\Tag;
use App\Models\User;

class FollowService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Follow());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new FollowService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    /**
     * 关注
     * @param $id
     * @param $userID
     * @param int $type 1作者 2标签
     * @return mixed
     */
    public static function follow($id, $userID, $type = 'user')
    {
        if ($type=='user') {
            $info = User::find($id);
        }elseif ($type=='tag') {
            $info = Tag::find($id);
        }else{
            $info = false;
        }

        if ($info) {
            //查询是否已点赞
            $exist = Follow::where([
                'moment_id' => $info->id,
                'type' => $type,
                'user_id' => $userID
            ])->exists();

            $data = [
                'moment_id' => $info->id,
                'type' => $type,
                'user_id' => $userID
            ];
            if ($exist) {
                if (Follow::where($data)->delete()) {
                    return false;
                }
            } else {
                if (Follow::create($data)) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }
}
