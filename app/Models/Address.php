<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 * 
 * @property int $id
 * @property int $uid
 * @property string $address
 * @property string $remark
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Address extends Model
{
	protected $table = 'address';

	protected $casts = [
		'uid' => 'int'
	];

	protected $fillable = [
		'uid',
		'address',
		'remark'
	];
}
