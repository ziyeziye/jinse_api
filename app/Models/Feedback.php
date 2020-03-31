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
 * @property int $user_id
 * @property int $type
 * @property string $content
 * @property string $nick_name
 * @property string $contact
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class Feedback extends Model
{
	protected $table = 'feedbacks';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'type' => 'string'
	];

    protected $appends = [
        'type_name'
    ];

    public function getTypeNameAttribute()
    {
        $type = $this->type;
        $types = ['功能建议','体验建议', '内容建议', '其他'];
        return $types[$type] ?? '其他';
    }

	protected $dates = [
		'create_time',
		'update_time'
	];

	protected $fillable = [
		'user_id',
		'type',
		'content',
		'nick_name',
		'contact',
		'create_time',
		'update_time'
	];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
