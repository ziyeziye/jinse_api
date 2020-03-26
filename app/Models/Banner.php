<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Banner
 *
 * @property int $id
 * @property string $name
 * @property string $img
 * @property string $href
 * @property int $r_id
 * @property bool $type
 *
 * @package App\Models
 */
class Banner extends Model
{
    protected $table = 'banners';
    public $timestamps = false;

    protected $casts = [
        'r_id' => 'int',
        'type' => 'string'
    ];

    protected $appends = [
        "img_src",
        "type_name"
    ];

    public function getImgSrcAttribute()
    {
        if (isUrl($this->img)) {
            return $this->img;
        }
        return $this->img ? env('APP_URL') . $this->img : '';
    }

    public function getTypeNameAttribute()
    {
        $type = $this->type;
        $types = ["默认","图片", "文章", "活动"];
        return isset($types[$type]) ? $types[$type] : "";
    }

    protected $fillable = [
        'name',
        'img',
        'href',
        'r_id',
        'type'
    ];
}
