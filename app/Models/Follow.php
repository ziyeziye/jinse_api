<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Follow
 *
 * @property int $id
 * @property int $user_id
 * @property int $moment_id
 * @property string $type 'user','tag'
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class Follow extends Model
{
	protected $table = 'follows';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
        'moment_id' => 'int',
        'type' => 'string',
	];

	protected $dates = [
		'create_time',
		'update_time'
	];

	protected $fillable = [
        'user_id',
        'moment_id',
        'type',
        'create_time',
        'update_time',
	];
}
