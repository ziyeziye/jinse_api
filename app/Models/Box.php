<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Box
 * 
 * @property int $id
 * @property int $uid
 * @property int $assets_id
 * @property int $amount
 * @property int $height
 * @property int $color
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Box extends Model
{
	protected $table = 'boxes';

	protected $casts = [
		'uid' => 'int',
		'assets_id' => 'int',
		'amount' => 'int',
		'height' => 'int',
		'color' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'uid',
		'assets_id',
		'amount',
		'height',
		'color',
		'status'
	];
}
