<?php

namespace App\Http\Api;

use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $page = $request->exists('pageNum') ? get_page() : [null];
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
            'article_id' => 'required|min:1',
            'user_id' => 'required',
            'content' => 'required|min:1',
        ], [
            'article_id.min' => '评论文章错误',
            'article_id.required' => '文章ID不能为空',
            'content.required' => '请输入评论内容',
            'content.min' => '评论内容不能为空',
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
            'content' => $request->input('content', ''),
            'user_id' => Auth::guard()->user()->id,
            'article_id' => $request->input('article_id'),
            'reply_id' => $request->input('reply_id', 0),
            're_reply_id' => $request->input('re_reply_id', 0),
            'reply_user_id' => $request->input('reply_user_id', 0),
            'zan' => $request->input('zan', 0),
        ];

        $result = $this->service()->save($data);
        return $this->successWithResult($result);
    }

    public function update(Request $request, $id)
    {
        //验证参数
        $this->valid();
        $data = [
            'content' => $request->input('content', ''),
            'user_id' => Auth::guard()->user()->id,
            'article_id' => $request->input('article_id'),
            'reply_id' => $request->input('reply_id', 0),
            're_reply_id' => $request->input('re_reply_id', 0),
            'reply_user_id' => $request->input('reply_user_id', 0),
            'zan' => $request->input('zan', 0),
        ];

        $result = $this->service()->update($data, $id);
        if (false === $result) {
            return $this->errorWithMsg('修改失败');
        }
        return $this->successWithResult($result);
    }

    public function delete(Request $request)
    {
        $ids = $request->input();
        $result = $this->service()->delete($ids);
        if (false === $result) {
            return $this->errorWithMsg('删除失败');
        }
        return $this->successWithResult($result);
    }

    public function articleComments(Request $request, $articleId)
    {
        //获取数据
        $page = $request->exists('pageNum') ? get_page() : [null];
        $where = request()->input();
        $where['article_id'] = $articleId;
        $result = $this->service()->articleComments($where, ...$page);
        return $this->successWithResult($result);
    }

    public function zan(Request $request, $id)
    {
        $userID = Auth::guard()->user()->id;
        $result = $this->service()->zan($id,$userID);
        if (false === $result) {
            return $this->errorWithMsg('修改失败');
        }
        return $this->successWithResult($result);
    }

}
