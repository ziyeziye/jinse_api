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
 * @property int $article_id
 * @property int $reply_id
 * @property int $reply_user_id
 * @property string $name
 * @property string $content
 * @property Carbon $create_time
 * @property Carbon $update_time
 * @property int $zan
 *
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

    ];

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

}
