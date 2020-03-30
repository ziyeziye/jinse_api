<?php

namespace App\Http\Api;

use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    protected $service;

    protected function service()
    {
        return PageService::instance();
    }

    public function table(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $where = request()->input();
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
            'type' => 'required',
            'content' => 'required',
        ], [
            'name.required' => '请输入标题',
            'type.required' => '请输入标识信息',
            'content.required' => '请输入内容',
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
            "content" => $request->input("content"),
            "type" => $request->input("type"),
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
            "content" => $request->input("content"),
            "type" => $request->input("type"),
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

    public function pageType(Request $request,$type)
    {
        $result = $this->service()->pageType($type);
        return $this->successWithResult($result);
    }

}
