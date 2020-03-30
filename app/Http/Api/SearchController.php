<?php

namespace App\Http\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends BaseController
{

    public function search(Request $request)
    {
        //获取数据
        $page = $request->exists("pageNum") ? get_page() : [null];
        $where = request()->input();
        $where['order_by'] = ["order" => "number", "desc" => "desc"];
        $result = ArticleService::table($where, ...$page);
        return $this->successWithResult($result);
    }


}
