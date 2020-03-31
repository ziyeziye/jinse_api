<?php

namespace App\Http\Api;

use App\Models\UserCoins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoinController extends BaseController
{

    /**
     * 市值榜
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function coinrank(Request $request)
    {

        $url = 'https://dncapi.bqiapp.com/api/coin/web-coinrank';
        $data = [
            "page" => $request->input('pageNum', 1),
            "pagesize" => $request->input('pageSize', 30),
            "type" => -1,
            "webp" => 1,
        ];
        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        return $this->successWithResult(json_decode($result));
    }

    /**
     * 涨跌幅榜
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function maxchange(Request $request)
    {
        $url = 'https://dncapi.bqiapp.com/api/v2/coin/maxchange';
        $data = [
            "isup" => $request->input('isup', 1),
            "per_page" => 30,
            "sort_type" => 4,
            "filtertype" => 0,
            "webp" => 1,
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        return $this->successWithResult(json_decode($result));
    }

    /**
     * 用户自选
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function coinfocus(Request $request)
    {
        $userID = Auth::guard()->user()->id;
        $codes = UserCoins::where('user_id', $userID)->groupBy('coin_code')->pluck('coin_code')->toArray();
        $result = '[]';
        if (!empty($codes)) {
            $url = 'https://api.coincap.io/v2/assets';
            $data = [
                "ids" => implode(',', $codes),
            ];
//            $header = [
//                'Host: docs.coincap.io',
//                'Origin: https://docs.coincap.io',
//                'Referer: https://docs.coincap.io/?version=latest',
//            ];
            $result = curlGet($url, $data, [], true);
        }

        return $this->successWithResult(json_decode($result));
    }

    /**
     * 货币详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request, $code)
    {
        $url = 'https://dncapi.bqiapp.com/api/coin/web-coininfo';
        $data = [
            "code" => $code,
            "webp" => 1,
            'token' => ''
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json, text/plain, */*'
        ];
        $result = curlPost($url, json_encode($data), $header, true);
        return $this->successWithResult(json_decode($result));
    }

    /**
     * 货币市场交易对
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markets(Request $request, $code)
    {
        $url = 'https://dncapi.bqiapp.com/api/coin/market_ticker';
        $data = [
            "page" => $request->input('pageNum', 1),
            "pagesize" => $request->input('pageSize', 50),
            "tickertype" => $request->input('tickertype', 0),    // 0现货交易的,1期货合约交易对
            "code" => $code,
            "webp" => 1,
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        $result = json_decode($result, true);
        if (isset($result['data']) && isset($result['data']['markets']) && !empty($result['data']['markets'])) {
            //查询是否已点赞
            $user = Auth::guard('api')->user();
            $userID = false;
            if ($user) {
                $userID = $user->id;
            }
            foreach ($result['data']['markets'] as $key => $market) {
                $result['data']['markets'][$key]['is_follow'] = false;
                if ($user) {
                    $result['data']['markets'][$key]['is_follow'] = UserCoins::where([
                        'user_id' => $userID,
                        'coin_code' => $market['coin_code'],
                        'exchange_code' => $market['exchange_code'],
                    ])->exists();
                }
            }
        }

        return $this->successWithResult($result);
    }

    /**
     * kline k线
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kline(Request $request, $code)
    {
        $url = 'https://dncapi.bqiapp.com/api/coin/web-charts';
        $data = [
            //y 年 ydt 今年 3m 3月 w 周 d 24小时 all 全部
            "type" => $request->input('type', 'all'),
            "code" => $code,
            "webp" => 1,
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        return $this->successWithResult(json_decode($result));
    }

    /**
     * 搜索货币/交易所
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $url = 'https://dncapi.bqiapp.com/api/search/websearch';
        $data = [
            "page" => $request->input('pageNum', 1),
            "exchange_page" => $request->input('exchangePage', 1),
            "wallet_page" => $request->input('walletPage', 1),
            "pagesize" => $request->input('pageSize', 50),
            "code" => $request->input('keyword', ''),
            "webp" => 1,
            "token" => '',
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        $result = json_decode($result, true);
//        if (isset($result['data']) && isset($result['data']['markets']) && !empty($result['data']['markets'])) {
//            //查询是否已点赞
//            $user = Auth::guard('api')->user();
//            $userID = false;
//            if ($user) {
//                $userID = $user->id;
//            }
//            foreach ($result['data']['markets'] as $key=>$market) {
//                $result['data']['markets'][$key]['is_follow'] = false;
//                if ($user) {
//                    $result['data']['markets'][$key]['is_follow'] = UserCoins::where([
//                        'user_id' => $userID,
//                        'coin_code' => $market['coin_code'],
//                    ])->exists();
//                }
//            }
//        }

        return $this->successWithResult($result);
    }

    /**
     * 交易所详情
     * @param Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange_info(Request $request, $code)
    {
        $url = 'https://dncapi.bqiapp.com/api/exchange/web-exchangeinfo';
        $data = [
            "code" => $code,
            "webp" => 1,
            'token' => ''
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        return $this->successWithResult(json_decode($result));
    }

    /**
     * 交易所公告
     * @param Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange_news(Request $request, $code)
    {
        $url = 'https://dncapi.bqiapp.com/api/v3/exchange/news';
        $data = [
            "page" => $request->input('pageNum', 1),
            "per_page" => $request->input('pageSize', 15),
            "exchangecode" => $code,
            "webp" => 1,
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
        ];
        $result = curlGet($url, $data, $header, true);
        return $this->successWithResult(json_decode($result));
    }


    /**
     * 交易所交易对行情列表
     * @param Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange_markets(Request $request, $code)
    {
        $url = 'https://dncapi.bqiapp.com/api/exchange/coinpair_list';
        $data = [
            "page" => $request->input('pageNum', 1),
            "pagesize" => $request->input('pageSize', 50),
            "code" => $code,
            "webp" => 1,
        ];

        $header = [
            'Host: mdncapi.bqiapp.com',
            'Origin: https://m.feixiaohao.com',
            'Referer: https://m.feixiaohao.com',
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json, text/plain, */*'
        ];
        $result = curlPost($url, json_encode($data), $header, true);
        return $this->successWithResult(json_decode($result));
    }


}
