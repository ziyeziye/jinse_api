<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'img_src',
        'type_name',
        'video_src',
        'is_zan',
        'is_good',
        'is_bad',
        'is_collected',
    ];

    public function getIsZanAttribute()
    {
        $isZan = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $userID = $user->id;
            $isZan = Zan::where([
                'moment_id' => $this->id,
                'type' => 'article',
                'user_id' => $userID
            ])->exists();
        }
        return $isZan;
    }

    public function getIsGoodAttribute()
    {
        $isZan = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $userID = $user->id;
            $isZan = Zan::where([
                'moment_id' => $this->id,
                'type' => 'article_good',
                'user_id' => $userID
            ])->exists();
        }
        return $isZan;
    }

    public function getIsBadAttribute()
    {
        $isZan = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $userID = $user->id;
            $isZan = Zan::where([
                'moment_id' => $this->id,
                'type' => 'article_bad',
                'user_id' => $userID
            ])->exists();
        }
        return $isZan;
    }

    public function getIsCollectedAttribute()
    {
        $isZan = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $userID = $user->id;
            $isZan = Collection::where([
                'article_id' => $this->id,
                'user_id' => $userID
            ])->exists();
        }
        return $isZan;
    }

    public function getImgSrcAttribute()
    {
        if (isUrl($this->img)) {
            return $this->img;
        }
        return $this->img ? env('APP_URL') . $this->img : '';
    }

    public function getVideoSrcAttribute()
    {
        if (isUrl($this->video)) {
            return $this->video;
        }
        return $this->video ? env('APP_URL') . $this->video : '';
    }

    public function getTypeNameAttribute()
    {
        $type = $this->type;
        $types = ['默认', '文章', '快讯', '视频'];
        return isset($types[$type]) ? $types[$type] : '';
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

    public function comments()
    {
        return $this->hasMany('App\Models\Comment','article_id','id');
    }

}
