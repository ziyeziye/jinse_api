<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Collection;

class CollectionService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Collection());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new CollectionService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = Article::query();
        if (isset($param['user_id']) && !empty($param['user_id'])) {
            $query = $query->where("collections.user_id", $param['user_id']);
        }else{
            $query = $query->whereRaw('0=1');
        }
        $query = $query->rightJoin('collections', 'article_id', '=', 'articles.id');
        $param['fields'] = ['articles.id', 'articles.name','articles.type','articles.user_id',
            'articles.img', 'articles.video','collections.create_time', 'collections.update_time'];
        $query = $query->with(['author' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

        $param['order_by'] = ["order" => "collections.create_time", "desc" => "desc"];
        return self::ModelSearch($query, $param, $page, $size);
    }


}
