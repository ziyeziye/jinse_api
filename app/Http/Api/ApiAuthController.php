<?php

namespace App\Http\Api;

use App\Services\SmsLogService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends BaseController
{
    protected $service;

    protected function service()
    {
        return UserService::instance();
    }

    public function login_sms(Request $request)
    {
        $check = $this->_valid([
            'verify_code' => 'required',
        ], [
            'phone.required' => '请输入手机号码',
            'verify_code.required' => '请输入验证码',
        ]);

        if (true !== $check) {
            return $this->errorWithMsg($check);
        }

        $phone = $request->input('phone');
        $verifyCode = $request->input('verify_code', '');
        $phone = $request->input('phone');
        $invite_uid = trim($request->input('invite_uid', 2));

//        if (!isMobile($phone)) {
//            return $this->errorWithMsg('手机号码不正确');
//        }

        if (empty($verifyCode)) {
            return $this->errorWithMsg('请输入验证码');
        }

        try {

            //TODO 调试成功,暂时不需要发送短信(默认验证码为111111)
            if ($verifyCode != '111111') {
                if (!SmsLogService::checkCode($phone, $verifyCode, 'register')) {
                    return $this->errorWithMsg('验证码错误');
                }
            }

            $data = [
                'username' => $phone,
                'phone' => $phone,
                'nickname' => '用户#' . $phone,
                'invite_uid' => $invite_uid,
                'status' => 1,
                'reg_ip' => $request->getClientIp(),
            ];

            $user = $this->service()->register_sms($data);
            if ($user) {
                //原h5登录流程
                session(["user_effective_{$user->id}" => time() + 3600]);
                session(['user' => $user]);

                //app api登录 默认记住我
                return $this->successWithResult(['user_id' => $user->id, 'api_token' => $user->generateToken(3600, true)]);
            } else {
                return $this->errorWithMsg("注册失败");
            }

        } catch (\Exception $e) {
            return $this->errorWithMsg($e->getMessage());
        }
    }

    public function login()
    {

    }

    public function user()
    {
        $user = Auth::guard()->user();
        return $this->successWithResult(Auth::guard()->user());
    }

    public function logout()
    {

    }

}
