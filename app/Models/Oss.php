<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Oss
 *
 * @property int $id
 * @property string $url
 * @property Carbon $create_time
 * @property int $document_type
 * @property int $type
 *
 * @package App\Models
 */
class Oss extends Model
{
	protected $table = 'oss';
	public $timestamps = false;

	protected $casts = [
		'document_type' => 'int',
		'type' => 'string'
	];

    protected $appends = [
        "type_name"
    ];

    public function getTypeNameAttribute()
    {
        $type = $this->type;
        $types = ["默认","图片", "文章", "活动"];
        return isset($types[$type]) ? $types[$type] : "";
    }

	protected $dates = [
		'create_time'
	];

	protected $fillable = [
		'url',
		'create_time',
		'document_type',
		'type'
	];
}
