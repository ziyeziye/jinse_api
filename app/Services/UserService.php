<?php

namespace App\Services;

use App\Models\Follow;
use App\Models\Password;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserCoins;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use exception;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new UserService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

    public static function table($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if (isset($param['username']) && !empty($param['username'])) {
            $query = $query->where('username', 'like', "%{$param['username']}%");
        }
        if (isset($param['phone']) && !empty($param['phone'])) {
            $query = $query->where('phone', 'like', "%{$param['phone']}%");
        }
        if (isset($param['status']) && !empty($param['status'])) {
            $query = $query->where('status', $param['status']);
        }
        return self::ModelSearch($query, $param, $page, $size);
    }

    /**
     * 用户注册
     * @param $username
     * @param $password
     * @param $encrypt_password
     * @param $ip
     * @return bool
     * @throws exception
     */
    public function register($data, $username, $password, $encrypt_password, $ip, $invite_uid = 0)
    {
        if ($username == '' || $password == '') {
            throw new exception('请完整填写信息', 50);
        }

        if (strlen($password) < 6 || strlen($password) > 20) {
            throw new exception('密码长度为6到20位', 51);
        }

        //用户主表用户名是否存在
        $userNum = User::where('username', $username)->count();

        if ($userNum > 0) {
            throw new exception('用户名已存在', 100);
        }

        if (isset($data['phone']) && !empty($data['phone'])) {
            //用户主表手机号是否存在
            $phoneNum = User::where('phone', $data['phone'])->count();

            if ($phoneNum > 0) {
                throw new exception('手机号已存在', 100);
            }
        }

        $salt = Str::random(6);//盐

        $user = new User();
        $user = $user->fill($data);

        $password = new Password();
        $password->password = User::HashPassword($user->prefix, $username, $encrypt_password, $salt);

        $user->salt = $salt;
        $user->username = $username;
        $user->reg_ip = $ip;
        $user->invite_uid = 0;

        if (is_numeric($invite_uid) && $invite_uid > 0) {
            // 判断邀请人是否存在
            if (User::find($invite_uid)) {
                $user->invite_uid = $invite_uid;
            }
        }

        DB::beginTransaction();
        try {
            if ($password->save() && $user->save()) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 用户登录
     * @param $phone
     * @param $encrypt_password
     * @param $username
     * @return mixed
     * @throws exception
     */
    public function login($phone, $encrypt_password, $username=false)
    {
        $user = false;
        if (!empty($phone)) {
            $user = User::where('phone', $phone)->first();
        }else if(!empty($username)){
            $user = User::where('username', $username)->first();
        }

        if (empty($user)) {
            throw new exception("用户不存在", 102);
        }

        if ($user->status != 1) {
            throw new exception("用户已禁用", 105);
        }

        $hash_password = new Password();
        $hash_password_str = User::HashPassword($user->prefix, $user->username, $encrypt_password, $user->salt);
        $encrypt_hash_password = $hash_password->where('password', $hash_password_str)->count();

        if (!$encrypt_hash_password) {
            throw new exception("密码错误", 103);
        }
        return $user;
    }

    public static function update($data, $id)
    {
        $info = self::$model->find($id);
        if ($info) {
            if (isset($data['phone']) && !empty($data['phone']) && $data['phone'] != $info->phone) {
                //用户主表手机号是否存在
                $phoneNum = User::where('phone', $data['phone'])->count();

                if ($phoneNum > 0) {
                    return false;
                }
            }
            $info = $info->fill($data);

            if (isset($data['password'])) {
                $salt = Str::random(6);//盐
                $password = new Password();
                $password->password = User::HashPassword($info->prefix, $info->username, $data['password'], $salt);
                if ($password->save()) {
                    $data['salt'] = $salt;
                } else {
                    //密码修改失败
                    return false;
                }
            }
            $info->update($data);
            if ($info->update($data)) {
                return $info;
            }
        }
        return false;
    }

    /**
     * 验证手机号
     */
    protected function checkMobile($mobile)
    {
        return preg_match('/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/i', $mobile);
    }

    /**
     * 通过手机号码注册用户,存在即返回用户信息
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     * @throws exception
     */
    public function register_sms($data)
    {
        //1.验证手机号码
        $phone = $data['phone'] ?? '';
        if (empty($phone)) {
            throw new exception('手机号码不能为空', 50);
        }
        if (!$this->checkMobile($phone)) {
            throw new exception('手机号不正确', 51);
        }

        //2.检查是否已存在的手机用户
        $exUser = self::$model->where('phone', $phone)->first();
        if ($exUser) {
            return $exUser;
        }

        //3.注册用户
        $user = new User();
        $user = $user->fill($data);
        // 判断邀请人是否存在
        if (is_numeric($user->invite_uid) && $user->invite_uid > 0 && !User::find($user->invite_uid)) {
            $user->invite_uid = 0;
        }

        $salt = Str::random(6);//盐
        $user->salt = $salt;
        if ($user->save()) {
            return $user;
        }

        throw new exception('用户注册失败', 52);
    }

    public static function follows($userID, int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        $query = $query->where('type', 'user');
        if (!empty($userID)) {
            $query = $query->where('follows.user_id', $userID);
        }else{
            $query = $query->whereRaw('0=1');
        }
        $query = $query->rightJoin('follows', 'moment_id', '=', 'users.id');
        $param['fields'] = ['users.id', 'users.username','users.nickname','users.avatar'];
        $param['order_by'] = ['order' => 'follows.create_time', 'desc' => 'desc'];
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function fans($userID, int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        $query = $query->where('type', 'user');
        if (!empty($userID)) {
            $query = $query->where('follows.moment_id', $userID);
        }else{
            $query = $query->whereRaw('0=1');
        }
        $query = $query->rightJoin('follows', 'user_id', '=', 'users.id');

        $param['fields'] = ['users.id', 'users.username','users.nickname','users.avatar', 'follows.create_time'];
        $param['order_by'] = ['order' => 'follows.create_time', 'desc' => 'desc'];
        return self::ModelSearch($query, $param, $page, $size);
    }

    public static function authors($param = [], int $page = null, int $size = 15)
    {
        $query = self::$model->query();
        if (isset($param['keyword'])) {
            $keyword = trim(strip_tags($param['keyword']));
            if (empty($keyword)) {
                $query = $query->whereRaw('1=0');
            }else {
                $query = $query->where(function ($query) use ($keyword) {
                    $query->orWhere('username', 'like', "%{$keyword}%");
                    $query->orWhere('nickname', 'like', "%{$keyword}%");
                });
            }
        }

        return self::ModelSearch($query, ['fields'=>['id','username','nickname','avatar']], $page, $size);
    }

    public function coinfocus($code, $exchange_code='',$userID)
    {
        $info = self::$model->find($userID);
        if ($info) {
            $data = [
                'coin_code' => $code,
                'user_id' => $userID,
                'exchange_code' => $exchange_code
            ];

            //查询是否已点赞
            $exist = UserCoins::where($data)->exists();
            if ($exist) {
                if (UserCoins::where($data)->delete()) {
                    return false;
                }
            } else {
                if (UserCoins::create($data)) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public static function getUser($id,$fields = ['*'])
    {
        return self::$model->select($fields)->find($id);
    }

}
