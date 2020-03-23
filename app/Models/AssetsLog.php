<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AssetsLog
 * 
 * @property int $id
 * @property int $assets_id
 * @property string $assets_name
 * @property int $uid
 * @property int $tx_id
 * @property string $operate_type
 * @property float $amount
 * @property float $amount_before_change
 * @property string $tx_hash
 * @property string $ip
 * @property string $user_agent
 * @property string $remark
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class AssetsLog extends Model
{
	protected $table = 'assets_logs';

	protected $casts = [
		'assets_id' => 'int',
		'uid' => 'int',
		'tx_id' => 'int',
		'amount' => 'float',
		'amount_before_change' => 'float'
	];

	protected $fillable = [
		'assets_id',
		'assets_name',
		'uid',
		'tx_id',
		'operate_type',
		'amount',
		'amount_before_change',
		'tx_hash',
		'ip',
		'user_agent',
		'remark'
	];
}
