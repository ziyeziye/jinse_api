<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Zan;
use Illuminate\Support\Facades\DB;

class CommentService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Comment());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new CommentService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        $query->with("user");
        $query->with("reply_user");
        $query->with("article");
        $query->with("reply");

        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function info($id)
    {
        $info = self::$model
            ->with("user")
            ->with("reply_user")
            ->with("article")
            ->with("reply")
            ->find($id);
        return $info;
    }

    public static function articleComments($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if (isset($param['article_id']) && !empty($param['article_id'])) {
            $query = $query->where("article_id", $param['article_id']);
        } else {
            $query = $query->whereRaw('0=1');
            return self::ModelSearch($query, $param, $page, $size);
        }

        if (isset($param['reply_id']) && !empty($param['reply_id'])) {
            $query = $query->where("reply_id", $param['reply_id']);
        } else {
            $query = $query->where("reply_id", 0);
        }

        $query = $query->with(['user' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }, 'replys' => function ($query) {
            $query->with(['user' => function ($query) {
                $query->select('id', 'username', 'nickname', 'avatar');
            }])->orderBy('zan', 'desc')->limit(3);
        }]);

        return self::ModelSearch($query, $param, $page, $size);
    }

    public function zan($id, $userID)
    {
        $info = self::$model->find($id);
        if ($info) {
            //查询是否已点赞
            $exist = Zan::where([
                'moment_id' => $info->id,
                'type' => 'comment',
                'user_id' => $userID
            ])->exists();

            DB::beginTransaction();
            try {
                if ($exist) {
                    Zan::where([
                        'moment_id' => $info->id,
                        'type' => 'comment',
                        'user_id' => $userID
                    ])->delete();
                    $zan = $info->zan - 1;
                } else {
                    Zan::create([
                        'moment_id' => $info->id,
                        'type' => 'comment',
                        'user_id' => $userID
                    ]);
                    $zan = $info->zan + 1;
                }

                $zan = $zan > 0 ? $zan : 0;
                if ($info->update(['zan' => $zan])) {
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

    public function info_comments($id, $param = [], int $page = null, int $size = 15)
    {
        $info = self::$model->with(['user' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }, 'article' => function ($query) {
            $query->select('id', 'name', 'type');
        }])->find($id);

        $query = self::$model->query();
        if (!$info) {
            $query = $query->whereRaw('0=1');
            return self::ModelSearch($query, $param, $page, $size);
        } else {
            $query->where('reply_id', $id);
        }

        $query = $query->with(['user' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

        $list = self::ModelSearch($query, $param, $page, $size)->toArray();
        $list['info'] = $info;
        return $list;
    }

    public function reply_msg($userId, int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if ($userId>0) {
            $query->where('reply_user_id', $userId)
            ->where('user_id','!=', $userId);
        } else {
            $query = $query->whereRaw('0=1');
            return self::ModelSearch($query, [], $page, $size);
        }

        $query = $query->with(['user' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

        $param['order_by'] = ["order" => "create_time", "desc" => "desc"];
        return self::ModelSearch($query, $param, $page, $size)->toArray();
    }

    public function zan_msg($userId, int $page = null, int $size = 15)
    {
        $query = Zan::query();
        if ($userId>0) {
            $query->where('comments.user_id', $userId)
                ->where('type', 'comment');
        } else {
            $query = $query->whereRaw('0=1');
            return self::ModelSearch($query, [], $page, $size);
        }

        $query = $query->rightJoin('comments', 'moment_id', '=', 'comments.id');

        $query = $query->with(['user' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

        $param['fields'] = ['comments.*', 'comments.user_id as comment_user_id','zans.create_time','zans.user_id'];
        $param['order_by'] = ["order" => "zans.create_time", "desc" => "desc"];
        return self::ModelSearch($query, $param, $page, $size)->toArray();
    }


}
