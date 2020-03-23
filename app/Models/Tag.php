<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * 
 * @property int $id
 * @property string $name
 * @property int $number
 * @property int $weight
 *
 * @package App\Models
 */
class Tag extends Model
{
	protected $table = 'tags';
	public $timestamps = false;

	protected $casts = [
		'number' => 'int',
		'weight' => 'int'
	];

	protected $fillable = [
		'name',
		'number',
		'weight'
	];
}
