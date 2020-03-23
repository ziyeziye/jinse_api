<?php

namespace App\Services;

use App\Models\HotWord;

class HotWordService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new HotWord());
    }

    private static $_object = null;

    public static function instance()
    {
        if (empty(self::$_object)) {
            self::$_object = new HotWordService(); //内部方法可以调用私有方法，因此这里可以创建对象
        }
        return self::$_object;
    }

}
