<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Collection;
use App\Models\Follow;
use App\Models\Zan;
use Illuminate\Support\Facades\DB;

class ArticleService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Article());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new ArticleService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        $param['fields'] = [
            'id','name','number','tags','type','create_time', 'img', 'update_time','user_id','good','bad'
        ];
        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        if (isset($param['type']) && !empty($param['type'])) {
            $type = is_array($param['type'])?$param['type']:explode(',', $param['type']);
            if ($param['type']==2) {
                $param['fields'][] = 'content';
            }
            $query = $query->whereIn("type", $type);
        }
        if (isset($param['category_id']) && !empty($param['category_id'])) {
            $query = $query->where("category_id", $param['category_id']);
        }
        $query->with(['author' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

//        $query->with("category");
        $query->withCount('comments');
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function info($id)
    {
        $info = self::$model
            ->with(['author' => function ($query) {
                $query->select('id', 'username', 'nickname', 'avatar');
            }])
            ->withCount("comments")
            ->find($id);
        return $info;
    }

    public function zan($id, $userID, $type = 'zan')
    {
        $zanTypes = ['zan' => 'article', 'good' => 'article_good', 'bad' => 'article_bad'];
        $info = self::$model->find($id);
        if ($info) {
            //查询是否已点赞
            $exist = Zan::where([
                'moment_id' => $info->id,
                'type' => $zanTypes[$type],
                'user_id' => $userID
            ])->exists();

            DB::beginTransaction();
            try {
                if ($exist) {
                    Zan::where([
                        'moment_id' => $info->id,
                        'type' => $zanTypes[$type],
                        'user_id' => $userID
                    ])->delete();
                    $zan = $info->$type - 1;
                } else {
                    Zan::create([
                        'moment_id' => $info->id,
                        'type' => $zanTypes[$type],
                        'user_id' => $userID
                    ]);
                    $zan = $info->$type + 1;
                }

                $zan = $zan > 0 ? $zan : 0;
                if ($info->update([$type => $zan])) {
                    DB::commit();
                } else {
                    DB::rollBack();
                }
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
        return $info;
    }

    public function collect($id, $userID)
    {
        $info = self::$model->find($id);
        if ($info) {
            //查询是否已点赞
            $exist = Collection::where([
                'article_id' => $info->id,
                'user_id' => $userID
            ])->exists();

            $data = [
                'article_id' => $info->id,
                'user_id' => $userID
            ];
            if ($exist) {
                if (Collection::where($data)->delete()) {
                    return false;
                }
            } else {
                if (Collection::create($data)) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }


    public static function follow_author($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        $userIds = [];
        if (isset($param['user_id']) && !empty($param['user_id'])) {
            $userIds = Follow::where([
                'user_id' => $param['user_id'],
                'type' => 'user',
            ])->pluck('moment_id')->toArray();

            if (!empty($userIds)) {
                $query = $query->with(['author' => function ($query) {
                    $query->select('id', 'username', 'nickname', 'avatar');
                }]);
                //        $query->withCount('comments');

                $param['fields'] = [
                    'id','name','number','tags','type','create_time', 'img', 'update_time','user_id','good','bad'
                ];
                $query = $query->whereIn('user_id', $userIds)
                ->whereIn('type',[1,3]);
                return self::ModelSearch($query, $param, $page, $size);
            }
        }

        $query = $query->whereRaw('0=1');
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function follow_tag($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        $userIds = [];
//        if (isset($param['user_id']) && !empty($param['user_id'])) {
//            $userIds = Follow::where([
//                'user_id' => $param['user_id'],
//                'type' => 'user',
//            ])->pluck('moment_id')->toArray();
//
//            if (!empty($userIds)) {
//                $query = $query->with(['author' => function ($query) {
//                    $query->select('id', 'username', 'nickname', 'avatar');
//                }]);
//                //        $query->withCount('comments');
//
//                $param['fields'] = [
//                    'id','name','number','tags','type','create_time', 'img', 'update_time','user_id','good','bad'
//                ];
//                $query = $query->whereIn('user_id', $userIds)
//                    ->whereIn('type',[1,3]);
//                return self::ModelSearch($query, $param, $page, $size);
//            }
//        }

        $query = $query->whereRaw('0=1');
        return self::ModelSearch($query, $param, $page, $size);
    }

}
