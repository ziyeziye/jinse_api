<?php

namespace App\Http\Api;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    protected $service;

    protected function service()
    {
        return CategoryService::instance();
    }

    public function table(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $where = request()->input();
        $where['order_by'] = ["order" => "sort", "desc" => "asc"];
        $result = $this->service()->table($where, ...$page);
        return $this->successWithResult($result);
    }

    public function info($id)
    {
        $result = $this->service()->info($id);
        return $this->successWithResult($result);
    }

    private function valid()
    {
        //验证参数
        $check = $this->_valid([
            'name' => 'required',
            'pid' => 'required',
            'root_id' => 'required',
        ], [
            'name.required' => '请输入名称',
            'pid.required' => '请选择上级分类',
            'root_id.required' => '请选择上级分类',
        ]);

        if (true !== $check) {
            return $this->errorWithMsg($check, 405);
        }
        return true;
    }

    public function save(Request $request)
    {
        $this->valid();
        $data = [
            "name" => $request->input("name"),
            "sort" => $request->input("sort", 999),
            "pid" => $request->input("pid", 0),
            "root_id" => $request->input("root_id", 0),
        ];

        $result = $this->service()->save($data);
        return $this->successWithResult($result);
    }

    public function update(Request $request, $id)
    {
        //验证参数
        $this->valid();
        $data = [
            "name" => $request->input("name"),
            "sort" => $request->input("sort", 999),
            "pid" => $request->input("pid", 0),
            "root_id" => $request->input("root_id", 0),
        ];

        $result = $this->service()->update($data, $id);
        if (false === $result) {
            return $this->errorWithMsg("修改失败");
        }
        return $this->successWithResult($result);
    }

    public function delete(Request $request)
    {
        $ids = $request->input();
        $result = $this->service()->delete($ids);
        if (false === $result) {
            return $this->errorWithMsg("删除失败");
        }
        return $this->successWithResult($result);
    }

    public function group()
    {
        $where['order_by'] = ["order" => "sort", "desc" => "asc"];
        $result = $this->service()->table($where);
        if ($result) {
            $result = $result->toArray();
            array_push($result, [
                "id" => 0,
                "name" => "一级分类",
                "open" => true,
                "pid" => -1,
                "root_id" => 0
            ]);
        }

        return $this->successWithResult($result);
    }

    public function tabbars(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $where['order_by'] = ["order" => "sort", "desc" => "asc"];
        $result = $this->service()->table($where)->toArray();

        array_unshift($result,['id'=>'follow','name'=>'关注'],['id'=>'top','name'=>'头条']);

        return $this->successWithResult($result);
    }
}
