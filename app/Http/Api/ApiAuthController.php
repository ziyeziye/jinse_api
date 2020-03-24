<?php

namespace App\Http\Api;

use App\Services\UserService;
use Illuminate\Http\Request;

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
            'phone' => 'required',
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

        print_r(isMobile($phone));die;


        try {
            $this->validator($request->all());
            $verifyCode = $request->input('verify_code', '');
            if (empty($verifyCode)) {
                throw new \Exception('请输入验证码');
            }

            $phone = $request->input('phone');
            if (!SmsLogService::checkCode($phone, $verifyCode, 'register')) {
                throw new \Exception('验证码错误');
            }

            $data = [
                'name' => '用户' . mt_rand(1000, 9999) . '_' . $phone,
                'avatar' => '../images/defalut_avatar.jpg',   //默认头像
//              'email' => $data['email'],
                'phone' => $phone,
                'password' => password_hash($request->input('password'), PASSWORD_DEFAULT),
                'phone_verified_at' => date('Y-m-d H:i:s'),
            ];

            event(new Registered($user = User::create($data)));
//            $this->guard()->login($user);
            return $this->registered($request, $user);

        } catch (\Exception $e) {
            return $this->reError($e->getMessage());
        }
    }

    public function login()
    {

    }

}
