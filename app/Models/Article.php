<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $cover
 * @property string $content
 * @property int $number
 * @property Carbon $create_time
 * @property Carbon $update_time
 * @property int $coin_id
 * @property string $tags
 * @property int $good
 * @property int $bad
 * @property int $category_id
 * @property string $video
 * @property bool $type
 * @property int $zan
 *
 * @package App\Models
 */
class Article extends Model
{
    protected $table = 'articles';
    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'number' => 'int',
        'coin_id' => 'int',
        'good' => 'int',
        'bad' => 'int',
        'category_id' => 'int',
        'zan' => 'int',
        'tags' => 'array',
        'type' => 'string',
    ];

    protected $dates = [
        'create_time',
        'update_time'
    ];

    protected $appends = [
        "img_src",
        "type_name",
        "video_src"
    ];

    public function getImgSrcAttribute()
    {
        return $this->img ? env('APP_URL') . $this->img : '';
    }

    public function getVideoSrcAttribute()
    {
        return $this->video ? env('APP_URL') . $this->video : '';
    }

    public function getTypeNameAttribute()
    {
        $type = $this->type;
        $types = ["默认", "文章", "快讯", "视频"];
        return isset($types[$type]) ? $types[$type] : "";
    }

    protected $fillable = [
        'user_id',
        'name',
        'img',
        'content',
        'number',
        'create_time',
        'update_time',
        'coin_id',
        'tags',
        'good',
        'bad',
        'category_id',
        'video',
        'type',
        'zan'
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

}
