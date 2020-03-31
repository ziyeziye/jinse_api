<?php

namespace App\Services;

use App\Models\Article;
use App\Models\History;

class HistoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new History());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new HistoryService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = Article::query();
        if (isset($param['user_id']) && !empty($param['user_id'])) {
            $query = $query->where("histories.user_id", $param['user_id']);
        }else{
            $query = $query->whereRaw('0=1');
        }
        $query = $query->rightJoin('histories', 'article_id', '=', 'articles.id');
        $param['fields'] = ['articles.id', 'articles.name','articles.type','articles.user_id',
            'articles.img', 'articles.video','histories.create_time', 'histories.update_time'];
        $query = $query->with(['author' => function ($query) {
            $query->select('id', 'username', 'nickname', 'avatar');
        }]);

        $param['order_by'] = ["order" => "histories.create_time", "desc" => "desc"];
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function save($data)
    {
        self::$model->where($data)->delete();
        return self::$model->create($data);
    }

}
