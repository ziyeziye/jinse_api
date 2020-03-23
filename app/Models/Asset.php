<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset
 * 
 * @property int $id
 * @property string $net_type
 * @property int $decimals
 * @property string $contract_address
 * @property string $assets_name
 * @property int $recharge_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Asset extends Model
{
	protected $table = 'assets';

	protected $casts = [
		'decimals' => 'int',
		'recharge_status' => 'int'
	];

	protected $fillable = [
		'net_type',
		'decimals',
		'contract_address',
		'assets_name',
		'recharge_status'
	];
}
