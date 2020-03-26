<?php

namespace App\Http\Api;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    protected $service;

    protected function service()
    {
        return UserService::instance();
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
        ], [
            'name.required' => '请输入名称',
            'img.required' => '请上传图标',
        ]);

        if (true !== $check) {
            return $this->errorWithMsg($check, 405);
        }
        return true;
    }

    /**
     * 摘至前台注册接口,做适当后台操作改动
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));
        $password2 = trim($request->input('password2'));
//        $encrypt_password = trim($request->input('encrypt_password'));
        $encrypt_password = hash("sha256", 'token' . $username . $password);

        $invite_uid = trim($request->input('invite_uid'));

        if ($password != $password2) {
//            return response()->json(['code' => 109, 'msg' => "两次密码不一致"]);
            return $this->errorWithMsg("两次密码不一致");
        }

        if (strlen($password) < 6 || strlen($password) > 20) {
//            return response()->json(['code' => 51, 'msg' => "密码长度为6到20位"]);
            return $this->errorWithMsg("密码长度为6到20位");
        }

        $data = $request->input();
        $ip = $request->getClientIp();
        try {
            $userService = new UserService();
            if ($userService->register($data, $username, $password, $encrypt_password, $ip, $invite_uid)) {
//                return response()->json(['code' => 0, 'msg' => "注册成功"]);
                return $this->successWithMsg("注册成功");
            } else {
//                return response()->json(['code' => 101, 'msg' => "注册失败"]);
                return $this->errorWithMsg("注册失败");
            }
        } catch (\exception $exception) {
//            return response()->json(['code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
            return $this->errorWithMsg($exception->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));
        $password2 = trim($request->input('password2'));
//        $encrypt_password = trim($request->input('encrypt_password'));
        $data = $request->input();
        unset($data["username"]);   //禁止修改用户名

        if (!empty($password)) {
            if ($password != $password2) {
                return $this->errorWithMsg("两次密码不一致");
            }

            if (strlen($password) < 6 || strlen($password) > 20) {
                return $this->errorWithMsg("密码长度为6到20位");
            }
            $encrypt_password = hash("sha256", 'token' . $username . $password);
            $data = [
                'encrypt_password' => $encrypt_password,
                'password' => $password
            ];
        } else {
            unset($data['password']);
        }
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

    public function follow_add(Request $request, $id)
    {
        $userID = Auth::guard()->user()->id;
        $result = $this->service()->follow($id,$userID,'user');
        return $this->successWithResult($result);
    }

    public function follows(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $userID = Auth::guard()->user()->id;
        $result = $this->service()->follows($userID, ...$page);
        return $this->successWithResult($result);
    }

    public function fans(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $userID = Auth::guard()->user()->id;
        $result = $this->service()->fans($userID, ...$page);
        return $this->successWithResult($result);
    }
}
