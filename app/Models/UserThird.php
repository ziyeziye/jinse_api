<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserThird
 * 
 * @property int $id
 * @property int $user_id
 * @property string $platform
 * @property string $openid
 * @property string $openname
 * @property string $access_token
 * @property string $refresh_token
 * @property int $expires_in
 * @property string $avatar
 * @property string $data
 *
 * @package App\Models
 */
class UserThird extends Model
{
	protected $table = 'user_thirds';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'expires_in' => 'int'
	];

	protected $hidden = [
		'access_token',
		'refresh_token'
	];

	protected $fillable = [
		'user_id',
		'platform',
		'openid',
		'openname',
		'access_token',
		'refresh_token',
		'expires_in',
		'avatar',
		'data'
	];
}
