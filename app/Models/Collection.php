<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Collection
 * 
 * @property int $id
 * @property int $user_id
 * @property int $article_id
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class Collection extends Model
{
	protected $table = 'collections';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'article_id' => 'int'
	];

	protected $dates = [
		'create_time',
		'update_time'
	];

	protected $fillable = [
		'user_id',
		'article_id',
		'create_time',
		'update_time'
	];
}
