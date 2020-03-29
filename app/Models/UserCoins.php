<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 *
 * @property int $id
 * @property int $user_id
 * @property string $coin_code
 * @property string $exchange_code
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class UserCoins extends Model
{
	protected $table = 'user_coins';
	public $timestamps = false;

    protected $dates = [
        'create_time',
        'update_time'
    ];

	protected $fillable = [
        'user_id',
        'coin_code',
        'exchange_code'
	];
}
