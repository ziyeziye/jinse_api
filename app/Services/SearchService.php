<?php

namespace App\Services;

use App\Models\Article;
use App\Models\BaseModel;

class SearchService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new BaseModel());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new SearchService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = Article::query();
        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        if (isset($param['type']) && !empty($param['type'])) {
            $type = is_array($param['type'])?:explode(',', $param['type']);
            $query = $query->whereIn("type", $type);
        }
        if (isset($param['category_id']) && !empty($param['category_id'])) {
            $query = $query->where("category_id", $param['category_id']);
        }
        $query->with(['author' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

        $param['fields'] = [
            'id','name','number','tags','type','create_time', 'img', 'update_time','user_id','good','bad'
        ];
//        $query->with("category");
        $query->withCount('comments');
        return self::ModelSearch($query, $param, $page, $size);
    }


}
