<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @property int $id
 * @property string $name
 * @property string $content
 * @property string $type
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class Page extends Model
{
	protected $table = 'pages';
	public $timestamps = false;

	protected $casts = [
        'type' => 'string',
	];

    protected $dates = [
        'create_time',
        'update_time'
    ];

	protected $fillable = [
		'name',
		'content',
		'type'
	];
}
