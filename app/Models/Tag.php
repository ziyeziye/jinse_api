<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Tag
 *
 * @property int $id
 * @property string $name
 * @property int $number
 * @property int $weight
 *
 * @package App\Models
 */
class Tag extends Model
{
	protected $table = 'tags';
	public $timestamps = false;

	protected $casts = [
		'number' => 'int',
		'weight' => 'int'
	];

	protected $fillable = [
		'name',
		'number',
		'weight'
	];

    protected $appends = [
        'is_follow',
    ];

    public function getIsFollowAttribute()
    {
        $isZan = false;
        $user = Auth::guard('api')->user();

        if ($user) {
            $userID = $user->id;
            $isZan = Follow::where([
                'moment_id' => $this->id,
                'type' => 'tag',
                'user_id' => $userID
            ])->exists();
        }
        return $isZan;
    }
}
