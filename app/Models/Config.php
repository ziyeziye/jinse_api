<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * 
 * @property int $id
 * @property string $param_key
 * @property string $param_value
 * @property int $status
 * @property string $remark
 *
 * @package App\Models
 */
class Config extends Model
{
	protected $table = 'configs';
	public $timestamps = false;

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'param_key',
		'param_value',
		'status',
		'remark'
	];
}
