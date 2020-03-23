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
 * @property string $name
 * @property string $content
 * @property string $href
 * @property Carbon $create_time
 * @property Carbon $update_time
 *
 * @package App\Models
 */
class Notice extends Model
{
    protected $table = 'notices';
    public $timestamps = false;

    protected $dates = [
        'create_time',
        'update_time'
    ];

    protected $fillable = [
        'name',
        'content',
        'href',
    ];
}
