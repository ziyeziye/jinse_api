<?php

namespace App\Http\Api;

use App\Services\HotWordService;
use Illuminate\Http\Request;

class HotWordController extends BaseController
{
    protected $service;

    protected function service()
    {
        return HotWordService::instance();
    }

    public function table(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $where = request()->input();
        $where['order_by'] = ["order" => "weight", "desc" => "desc"];
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
        ], [
            'name.required' => '请输入名称',
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
            "number" => $request->input("number"),
            "weight" => $request->input("weight"),
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
            "number" => $request->input("number"),
            "weight" => $request->input("weight"),
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

}
