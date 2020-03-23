<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * 
 * @property int $id
 * @property string $from
 * @property string $to
 * @property string $hash
 * @property string $block_hash
 * @property int $block_number
 * @property float $gas_price
 * @property float $amount
 * @property int $status
 * @property int $tx_status
 * @property string $assets_type
 * @property int $token_id
 * @property int $data_id
 * @property string $remark
 * @property int $admin_id
 * @property string $payee
 * @property float $token_tx_amount
 * @property int $uid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Transaction extends Model
{
	protected $table = 'transactions';

	protected $casts = [
		'block_number' => 'int',
		'gas_price' => 'float',
		'amount' => 'float',
		'status' => 'int',
		'tx_status' => 'int',
		'token_id' => 'int',
		'data_id' => 'int',
		'admin_id' => 'int',
		'token_tx_amount' => 'float',
		'uid' => 'int'
	];

	protected $fillable = [
		'from',
		'to',
		'hash',
		'block_hash',
		'block_number',
		'gas_price',
		'amount',
		'status',
		'tx_status',
		'assets_type',
		'token_id',
		'data_id',
		'remark',
		'admin_id',
		'payee',
		'token_tx_amount',
		'uid'
	];
}
