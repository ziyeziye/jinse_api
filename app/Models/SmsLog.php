<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    //
    protected $fillable = [
        'type',
        'scene',
        'user_id',
        'phone',
        'code',
        'checked',
        'status',
        'reason',
        'remark',
        'ip',
    ];

    //类型(0:短信验证码,1:语音验证码,2:短信消息通知)
    const TYPE_CODE = 0;
    const TYPE_VOICE = 1;
    const TYPE_MESSAGE = 2;

    //是否验证(0:未验证,1:已验证)
    const CHECKED_UNVERIFIED = 0;
    const CHECKED_VERIFIED = 1;

    //状态(0:未发送,1:已发送,2:发送失败)
    const STATUS_NO_SEND = 0;
    const STATUS_SEND = 1;
    const STATUS_FAIL = 2;

    //短信发送间隔时间，默认60秒
    const SEND_INTERVAL_TIME = 60;
}
