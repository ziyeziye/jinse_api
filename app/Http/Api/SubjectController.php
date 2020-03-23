<?php

namespace App\Http\Api;

use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends BaseController
{
    protected $service;

    protected function service()
    {
        return SubjectService::instance();
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
            'img' => 'required',
            'content' => 'required',
            'user_id' => 'required',
        ], [
            'name.required' => '请输入名称',
            'img.required' => '请上传图片',
            'content.required' => '请输入导读内容',
            'user_id.required' => '请选择作者',
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
            "img" => $request->input("img"),
            "content" => $request->input("content"),
            "user_id" => $request->input("user_id", 0),
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
            "img" => $request->input("img"),
            "content" => $request->input("content"),
            "user_id" => $request->input("user_id", 0),
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

    public function articles(Request $request, $id)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $where = request()->input();
        $where["subject_id"] = $id;
        $result = $this->service()->subject_articles($where, ...$page);
        return $this->successWithResult($result);
    }

    public function add_article(Request $request, $id)
    {
        $ids = $request->input();
        $result = $this->service()->add_article($id, $ids);
        if (false === $result) {
            return $this->errorWithMsg("添加失败");
        }
        return $this->successWithResult($result);
    }

    public function del_article(Request $request, $id)
    {
        $ids = $request->input();
        $result = $this->service()->del_article($id, $ids);
        if (false === $result) {
            return $this->errorWithMsg("删除失败");
        }
        return $this->successWithResult($result);
    }

}
