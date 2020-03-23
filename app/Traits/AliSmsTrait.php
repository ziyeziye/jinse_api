<?php

namespace App\Traits;

use Mrgoon\AliSms\AliSms;

/**
 * 阿里云短信类
 */
trait AliSmsTrait
{
    //模板CODE
    public static $templateCodes = [
        "login" => 'SMS_XXXXXXXXXX',    //登录
        "register" => 'SMS_181861584', //注册
        "forgot_pwd" => 'SMS_181856607',   //忘记密码
        "reset_pwd" => 'SMS_XXXXXXXXXX',    //重置密码
        "verify_code" => 'SMS_XXXXXXXXXX',    //通用验证码
    ];

    /**
     * 发送验证码
     * @param $mobile 手机号码
     * @param $scene 业务场景
     * @param array $data 数据 array('key1'=>'value1','key2'=>'value2', …… )
     * @return bool
     * @throws \Exception
     */
    public static function sendSms($mobile, $scene, $data = [])
    {
        if (empty($mobile)) {
            throw new \Exception('手机号不能为空');
        }

        if (empty($scene)) {
            throw new \Exception('场景不能为空');
        }

        if (!isset(self::$templateCodes[$scene])) {
            throw new \Exception('请配置场景的模板CODE');
        }

        $templateCode = self::$templateCodes[$scene];

        try {
            $aliSms = new AliSms();
            $response = $aliSms->sendSms($mobile, $templateCode, $data);

            if ($response->Code == 'OK') {
                return true;
            }
            throw new \Exception($response->Message);
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
