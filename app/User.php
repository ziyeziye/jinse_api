<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'verify_id' => 'int',
        'invite_uid' => 'int',
        'status' => 'int'
    ];

    protected $hidden = [
        'salt', 'remember_token', 'api_token'
    ];

    protected $fillable = [
        'nickname',
        'phone',
        'verify_id',
        'avatar',
        'data',
        'username',
        'invite_uid',
        'status',
        'salt',
        'reg_ip',
        'remember_token',
        'api_token',
        'expires_at',
    ];

    protected $appends = [
        'avatar_src',
        'status_name',
        'verify_name',
        'invite_user'
    ];

    public function getAvatarSrcAttribute()
    {
        return $this->avatar ? env('APP_URL') . $this->avatar : '';
    }

    public function getStatusNameAttribute()
    {
        $type = $this->status;
        $types = ['默认', '正常', '禁用'];
        return isset($types[$type]) ? $types[$type] : '';
    }

    public function getVerifyNameAttribute()
    {
        $verifyID = $this->verify_id;
        if ($verifyID == 0) {
            return '未认证';
        }
    }

    public function getInviteUserAttribute()
    {
        $inviteID = $this->invite_uid;
        if ($inviteID == 0) {
            return [];
        } else {
            return self::find($this->invite_uid);
        }
    }
}
