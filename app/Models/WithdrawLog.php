<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WithdrawLog
 * 
 * @property int $id
 * @property int $uid
 * @property string $assets_type
 * @property string $address
 * @property float $amount
 * @property float $fee
 * @property int $status
 * @property string $tx_hash
 * @property string $ip
 * @property string $user_agent
 * @property string $msg
 * @property int $code
 * @property int $hour
 * @property string $remark
 * @property int $tx_status
 * @property string $net_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class WithdrawLog extends Model
{
	protected $table = 'withdraw_log';

	protected $casts = [
		'uid' => 'int',
		'amount' => 'float',
		'fee' => 'float',
		'status' => 'int',
		'code' => 'int',
		'hour' => 'int',
		'tx_status' => 'int'
	];

	protected $fillable = [
		'uid',
		'assets_type',
		'address',
		'amount',
		'fee',
		'status',
		'tx_hash',
		'ip',
		'user_agent',
		'msg',
		'code',
		'hour',
		'remark',
		'tx_status',
		'net_type'
	];
}
