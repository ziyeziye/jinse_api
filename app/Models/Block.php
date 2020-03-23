<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Block
 * 
 * @property int $id
 * @property int $height
 * @property string $hash
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Block extends Model
{
	protected $table = 'block';

	protected $casts = [
		'height' => 'int'
	];

	protected $fillable = [
		'height',
		'hash'
	];
}
