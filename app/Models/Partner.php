<?php

namespace App\Models;

class Partner extends BaseModel
{
    protected $fillable = [
        "id",
        "name",
        "img",
        "href",
        "create_time",
        "update_time",
    ];

    protected $appends = [
        "img_src",
    ];

    public function getImgSrcAttribute()
    {
        if (isUrl($this->img)) {
            return $this->img;
        }
        return $this->img ? env('APP_URL') . $this->img : '';
    }
}
