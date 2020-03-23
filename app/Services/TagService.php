<?php

namespace App\Services;

use App\Models\Tag;

class TagService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Tag());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new TagService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

}
