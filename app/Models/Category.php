<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property int $id
 * @property string $name
 * @property int $pid
 * @property int $sort
 * @property int $root_id
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';
	public $timestamps = false;

	protected $casts = [
		'pid' => 'int',
		'sort' => 'int',
		'root_id' => 'int'
	];

	protected $fillable = [
		'name',
		'pid',
		'sort',
		'root_id'
	];

    protected $appends = [
        "parent_name",
    ];

    public function getParentNameAttribute()
    {
        $parentName = self::where("id", $this->pid)->value("name");
        return $parentName ?: "一级分类";
    }
}
