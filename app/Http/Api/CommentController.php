<?php

namespace App\Http\Api;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    protected $service;

    protected function service()
    {
        return CommentService::instance();
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
            'img' => 'required',
            'article_id' => 'required',
            'user_id' => 'required',
            'comment' => 'required',
        ], [
            'img.required' => '请上传封面',
            'user_id.required' => '用户ID不能为空',
            'article_id.required' => '文章ID不能为空',
            'comment.required' => '请输入评论内容',
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
            "content" => $request->input("content", ''),
            "user_id" => $request->input("user_id"),
            "article_id" => $request->input("user_id"),
            "reply_id" => $request->input("category_id", 0),
            "reply_user_id" => $request->input("category_id", 0),
            "zan" => $request->input("zan", 0),
        ];

        $result = $this->service()->save($data);
        return $this->successWithResult($result);
    }

    public function update(Request $request, $id)
    {
        //验证参数
        $this->valid();
        $data = [
            "content" => $request->input("content", ''),
            "user_id" => $request->input("user_id"),
            "article_id" => $request->input("user_id"),
            "reply_id" => $request->input("category_id", 0),
            "reply_user_id" => $request->input("category_id", 0),
            "zan" => $request->input("zan", 0),
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
