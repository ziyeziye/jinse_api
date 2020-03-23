<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubjectArticle
 *
 * @property int $id
 * @property int $subject_id
 * @property int $article_id
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class SubjectArticle extends Model
{
	protected $table = 'subject_articles';
	public $timestamps = false;

	protected $casts = [
		'subject_id' => 'int',
		'article_id' => 'int'
	];

    protected $dates = [
        'create_time',
        'update_time'
    ];

	protected $fillable = [
		'subject_id',
		'article_id',
        'create_time',
        'update_time'
	];

    public function article(){
        return $this->belongsTo('App\Models\Article',"article_id");
    }
}
