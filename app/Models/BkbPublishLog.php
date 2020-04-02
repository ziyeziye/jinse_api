<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Feedback
 *
 * @property int $id
 * @property int $article_id
 * @property string $bkb_id
 * @property string $businessNo
 * @property string $tag
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class BkbPublishLog extends Model
{
	protected $table = 'bkb_publish_logs';
	public $timestamps = false;

	protected $casts = [
		'article_id' => 'int',
		'bkb_id' => 'string'
	];

	protected $dates = [
		'create_time',
		'update_time'
	];

	protected $fillable = [
        'bkb_id',
        'article_id',
        'businessNo',
        'tag',
		'create_time',
		'update_time'
	];
}
