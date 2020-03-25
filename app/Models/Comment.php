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
 * @property int $article_id
 * @property int $reply_id
 * @property int $reply_user_id
 * @property string $name
 * @property string $content
 * @property Carbon $create_time
 * @property Carbon $update_time
 * @property int $zan
 * @property int $re_reply_id
 * @package App\Models
 */
class Comment extends Model
{
    protected $table = 'comments';
    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'article_id' => 'int',
        'reply_id' => 'int',
        'reply_user_id' => 'int',
        'zan' => 'int',
    ];

    protected $dates = [
        'create_time',
        'update_time'
    ];


    protected $fillable = [
        'user_id',
        'article_id',
        'content',
        'reply_id',
        'reply_user_id',
        'create_time',
        'update_time',
        'zan',
        're_reply_id',
    ];

    protected $appends = [
        "is_zan",
    ];

    public function getIsZanAttribute()
    {
        $isZan = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $userID = $user->id;
            $isZan = Zan::where([
                'moment_id' => $this->id,
                'type' => 'comment',
                'user_id' => $userID
            ])->exists();
        }
        return $isZan;
    }

    public function getContentAttribute($content)
    {
        $reID = $this->re_reply_id;
        if ($reID > 0) {
            $reInfo = self::query()->with(['user'])->find($reID);
            if ($reInfo) {
                $nickname = $reInfo->user ? $reInfo->user->nickname : '账号已注销';
                $content .= ' //@' . $nickname . ': ' . $reInfo->content;
            }
        }
        return $content;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function reply_user()
    {
        return $this->belongsTo('App\Models\User', 'reply_user_id');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Article', 'article_id');
    }

    public function reply()
    {
        return $this->belongsTo('App\Models\Comment', 'reply_id');
    }

    public function replys()
    {
        return $this->hasMany('App\Models\Comment', 'reply_id', 'id');
    }
}
