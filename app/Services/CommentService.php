<?php

namespace App\Services;

use App\Models\Comment;

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
        $info =self::$model
            ->with("user")
            ->with("reply_user")
            ->with("article")
            ->with("reply")
            ->find($id);
        return $info;
    }
}
