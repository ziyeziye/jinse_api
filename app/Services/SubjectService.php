<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Subject;
use App\Models\SubjectArticle;

class SubjectService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Subject());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new SubjectService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        $query->with("author");

        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function info($id)
    {
        return self::$model->with("author")->find($id);
    }

    public static function subject_articles($param = [], int $page = null, int $size = 15)
    {
        $query = Article::query();
        if (!isset($param["subject_id"]) || $param["subject_id"] < 1) {
            return self::ModelSearch($query->whereRaw('1=0'), $param, $page, $size);
        }
        $query->rightJoin('subject_articles', 'article_id', '=', 'articles.id')
            ->where("subject_id", $param["subject_id"])
            ->select('articles.*', 'subject_articles.id', 'subject_articles.create_time');

        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        if (!empty($ids)) {
            $query = $query->whereIn("articles.id", $ids);
        }
        $query->with("author");
//        $query->with("category");
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function add_article($id, $ids)
    {
        $info = self::info($id);
        if ($info) {
            if (!empty($ids)) {
                $temp = [];
                foreach ($ids as $item) {
                    $data = ['article_id' => $item, 'subject_id' => $id];
                    SubjectArticle::where($data)->delete();
                    $temp[] = $data;
                }
                return SubjectArticle::insert($temp);
            }
        }
        return false;
    }

    public static function del_article($id, $ids)
    {
        if (!empty($ids)) {
            return SubjectArticle::whereIn('id', $ids)
                ->where('subject_id', $id)->delete();
        }
        return false;
    }
}
