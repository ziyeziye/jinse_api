<?php

namespace App\Http\Api;

use App\Services\FeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends BaseController
{
    protected $service;

    protected function service()
    {
        return FeedbackService::instance();
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
            'type' => 'required',
            'content' => 'required',
            'nick_name' => 'required',
            'contact' => 'required',
        ], [
            'type.required' => '请选择反馈类型',
            'content.required' => '请输入内容',
            'nick_name.required' => '请输入联系人',
            'contact.required' => '请输入联系方式',
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
            "type" => $request->input("type"),
            "content" => $request->input("content"),
            "nick_name" => $request->input("nick_name"),
            "contact" => $request->input("contact"),
            "user_id" => 0,
        ];

        $user = Auth::guard('api')->user();
        if ($user) {
            $data['user_id'] = $user->id;
        }

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
            "href" => $request->input("href"),
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
