<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subject
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $cover
 * @property string $content
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class Subject extends Model
{
	protected $table = 'subjects';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int'
	];

	protected $dates = [
		'create_time',
		'update_time'
	];

    protected $appends = [
        "img_src",
    ];

    public function getImgSrcAttribute()
    {
        if (isUrl($this->img)) {
            return $this->img;
        }
        return $this->img ? env('APP_URL') . $this->img : '';
    }

	protected $fillable = [
		'user_id',
		'name',
		'img',
		'content',
		'create_time',
		'update_time'
	];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
