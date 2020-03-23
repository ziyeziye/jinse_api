<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    public static $model;

    public function __construct(Model $model)
    {
        self::$model = $model;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if (isset($param['name']) && !empty($param['name'])) {
            $query = $query->where("name", "like", "%{$param['name']}%");
        }
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function info($id)
    {
        return self::$model->find($id);
    }

    public static function save($data)
    {
        return self::$model->create($data);
    }

    public static function update($data, $id)
    {
        $info = self::$model->find($id);
        if ($info) {
            $info->update($data);
        }
        return $info;
    }

    public static function delete($ids)
    {
        if (!empty($ids)) {
            if (self::$model->whereIn("id", $ids)->delete()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $model
     * @param array $option
     * @param int|null $page
     * @param int $size
     * @return mixed
     */
    public static function ModelSearch($model, $option = [], int $page = null, int $size = 15)
    {
        if (isset($option['order_by']) && is_array($option['order_by'])) {
            $model = $model->orderBy($option['order_by']['order'], $option['order_by']['desc']);
        }

        if (!isset($option['fields'])) {
            $option['fields'] = ['*'];
        }

        if (is_numeric($page)) {
            $data = $model->paginate($size, $option['fields'], 'page', $page);
        } else {
            $data = $model->select($option['fields'])->get();
        }
        return $data;
    }
}
