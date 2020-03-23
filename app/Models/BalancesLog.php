<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BalancesLog
 * 
 * @property int $id
 * @property int $assets_id
 * @property int $uid
 * @property string $operate_type
 * @property float $amount
 * @property float $amount_before_change
 * @property string $assets_name
 * @property string $tx_hash
 * @property int $tx_id
 * @property string $ip
 * @property string $user_agent
 * @property string $remark
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class BalancesLog extends Model
{
	protected $table = 'balances_logs';

	protected $casts = [
		'assets_id' => 'int',
		'uid' => 'int',
		'amount' => 'float',
		'amount_before_change' => 'float',
		'tx_id' => 'int'
	];

	protected $fillable = [
		'assets_id',
		'uid',
		'operate_type',
		'amount',
		'amount_before_change',
		'assets_name',
		'tx_hash',
		'tx_id',
		'ip',
		'user_agent',
		'remark'
	];
}
