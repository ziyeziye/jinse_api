<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class User
 *
 * @property int $id
 * @property string $nickname
 * @property string $phone
 * @property int $verify_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $avatar
 * @property string $data
 * @property string $username
 * @property int $invite_uid
 * @property int $status
 * @property string $salt
 * @property string $reg_ip
 * @property string $remember_token
 * @property string $api_token
 * @property Carbon $expires_at
 *
 * @package App\Models
 */
class Users extends Model{

    protected $table = 'users';
    public $prefix = 'token';

    protected $casts = [
        'verify_id' => 'int',
        'invite_uid' => 'int',
        'status' => 'int'
    ];

    protected $hidden = [
        'salt',
        'remember_token', 'api_token'
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

    //父子关系最大限制代数
    public static $P_TREE_LAYER_COUNT = 32;

    /**
     * 生产密码
     * @param $prefix
     * @param $username
     * @param $password
     * @param $salt
     * @return string
     */
    public static function hashPassword($prefix, $username, $password, $salt)
    {
        return hash('sha256', $prefix . $username . $password . $salt, false);
    }


    /**
     * 创建昵称
     * @param string $lang
     * @return string
     */
    public static function createNickname($lang = 'zh')
    {
        while (true) {
            $nickname = trans('international.user', [], $lang) . '_' . Str::random(8);

            if (self::where('nickname', $nickname)->count() == 0) {
                return $nickname;
            }
        }
    }

    /**
     * 创建邀请码
     */
    public static function createCodeInvite()
    {
        while (true) {
            $code_invite = Str::random(6);

            if (self::where('code_invite', $code_invite)->count() == 0) {
                return $code_invite;
            }
        }
    }

    /**
     * 生成api_token
     * @param int $expiresTime 过期时间,默认30分钟
     * @param bool $remember 记住我
     * @return string
     */
    public function generateToken($expiresTime = 1800, $remember = false)
    {
        $token = Str::random(80);
        $api_token = hash('sha256', $token);
        $this->api_token = $api_token;
        $this->expires_at = date('Y-m-d H:i:s', time() + $expiresTime);
        if ($remember) {
            $this->remember_token = $this->api_token;
        } else {
            $this->remember_token = '';
        }
        $this->save();
        return $token;
    }
}
