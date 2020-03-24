<?php

namespace App\Services;

use App\Models\Article;

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
        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        if (isset($param['type']) && !empty($param['type'])) {
            $query = $query->where("type", $param['type']);
        }
        $query->with("author");
        $query->with("category");

        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function info($id)
    {
        $info =self::$model->with("author")->find($id);
        return $info;
    }

}
