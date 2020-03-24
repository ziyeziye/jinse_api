<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Traits\AliSmsTrait;

/**
 * @package App\Services
 */
class SmsLogService extends BaseService
{
    use AliSmsTrait;

    /**
     * 验证短信验证码
     * @param $mobile
     * @param $code
     * @param string $scene
     * @return bool
     * @throws \Exception
     */
    public static function checkCode($mobile, $code, $scene = "verify_code")
    {
        if (!$mobile) {
            throw new \Exception('手机号不能为空');
        }

        if (!self::checkMobile($mobile)) {
            throw new \Exception('手机号不正确');
        }

        if (!$code) {
            throw new \Exception('验证码不能为空');
        }

        $sms_log = SmsLog::where([
            ['type', SmsLog::TYPE_CODE],
            ['phone', $mobile],
            ['status', SmsLog::STATUS_SEND],
            ['checked', SmsLog::CHECKED_UNVERIFIED],
            ['scene', $scene]
        ])->orderBy('created_at', 'desc')->first();

        if (!$sms_log) {
            throw new \Exception('验证码不存在,请重新获取');
        }

        if ($code != $sms_log->code) {
            throw new \Exception('验证码错误');
        }

        $sms_log->checked = SmsLog::CHECKED_VERIFIED;
        $sms_log->save();

        return true;
    }

    /**
     * 检测短信频率
     * @param $mobile
     * @param string $scene
     * @return bool
     * @throws \Exception
     */
    protected static function checkRate($mobile, $scene = "verify_code")
    {
        if (!$mobile) {
            throw new \Exception('手机号不能为空');
        }

        $sms_log = SmsLog::where([
            ['phone', $mobile],
            ['status', SmsLog::STATUS_SEND],
            ['scene', $scene]
        ])->orderBy('created_at', 'desc')->first();

        $now = time();

        if ($sms_log) {
            if (($now - strtotime($sms_log->created_at)) < SmsLog::SEND_INTERVAL_TIME) {
                throw new \Exception('短信发送太频繁,请稍后再试');
            }
        }

        return true;
    }

    /**
     * 验证手机号
     */
    protected static function checkMobile($mobile)
    {
        return preg_match('/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/i', $mobile);
    }

    /**
     * 发送短信验证码
     * @param $mobile
     * @param string $scene 业务场景,模板
     * @param array $data 数据
     * @return bool
     * @throws \Exception
     */
    public static function sendCode($mobile, $scene = "verify_code", $data = [])
    {
        self::checkRate($mobile, $scene);

        $code = mt_rand(100000, 999999);
        $sms_log = SmsLog::create([
            'type' => SmsLog::TYPE_CODE,
            'phone' => $mobile,
            'code' => $code,
            'checked' => SmsLog::CHECKED_UNVERIFIED,
            'status' => SmsLog::STATUS_NO_SEND,
            'ip' => getRealIp(),
            'scene' => $scene
        ]);

        try {
            AliSmsTrait::sendSms($mobile, $scene, array_merge($data, ['code' => $code]));

            $sms_log->status = SmsLog::STATUS_SEND;
            $sms_log->save();

            return true;
        } catch (\Exception $e) {
            $sms_log->status = SmsLog::STATUS_FAIL;
            $sms_log->reason = $e->getMessage();
            $sms_log->save();
            throw new \Exception($e->getMessage());
        }
    }
}
