<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Balance
 * 
 * @property int $id
 * @property int $uid
 * @property int $assets_id
 * @property string $name
 * @property float $amount
 * @property float $freeze_amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Balance extends Model
{
	protected $table = 'balances';

	protected $casts = [
		'uid' => 'int',
		'assets_id' => 'int',
		'amount' => 'float',
		'freeze_amount' => 'float'
	];

	protected $fillable = [
		'uid',
		'assets_id',
		'name',
		'amount',
		'freeze_amount'
	];
}
