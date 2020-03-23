<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HotWord
 * 
 * @property int $id
 * @property string $name
 * @property int $number
 * @property int $weight
 *
 * @package App\Models
 */
class HotWord extends Model
{
	protected $table = 'hot_words';
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
