<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\BkbPublishLog;
use App\Models\User;
use App\Services\PinYinService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BkbNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bkb:pull';

    //生成环境
    protected $bkbUrl = 'http://api-qa.beekuaibao.com/thirdparty/getOpenData/V2';
    protected $bkbKey = 'bkb88888888';
    protected $bkbChannel = 'swft';

    //生成环境
//    protected $bkbUrl = 'https://api.beekuaibao.com/thirdparty/getOpenData/V2';
//    protected $bkbKey = '07360FD5946F9F7620942E18EA9EFF48';
//    protected $bkbChannel = 'cherry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取bkb最新资讯(自动获取各种tag)、快讯';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     *
     * @return mixed
     */
    public function handle()
    {
        //获取快讯
        $this->pull_bkb_news('B100000');
        //获取资讯
        $this->pull_bkb_news('B100001','', 1);
        //推荐资讯
        $this->pull_bkb_news('B100001', 'recommend', 1);
        //每⽇⾏情
        $this->pull_bkb_news('B100001', 'dailyMarket', 2);
        //币圈秘事
        $this->pull_bkb_news('B100001', 'beeMystery', 3);
        //新⼿必读
        $this->pull_bkb_news('B100001', 'noviceRead', 4);
    }

    /**
     * 获取资讯
     * @param string $businessNo
     * @param string $tag
     * @param int $categoryId
     * //  测试地址：http://api-qa.beekuaibao.com/thirdparty/getOpenData/V2
     *             key bkb88888888,channel swft
     * //  ⽣产地址：https://api.beekuaibao.com/thirdparty/getOpenData/V2
     *             key 07360FD5946F9F7620942E18EA9EFF48 , channel cherry
     * //  请求头：ContentType为APPLICATION/JSON
     * //  请求⽅式：post
     * //  请求参数：json对象，格式如下：
     * //  {
     * //      'channel':'xxxxxx',//渠道，由bitwires提供
     * //      'sign':'F4C51711AAA195C1794E93D6E3E4C415F0126236EA0318AB1B6D3AA777B3B3A96E7A86F6FCAACCD1870B
     * //      E018A1F9464F39BBA27800F544380FDD3F7835DD0',// 签名，SHA512(key+data内的字符串（包含⼤括号）)
     * //      'data':{//注意：data中的字段要严格按照此顺序
     * //      'businessNo':'B100000',//业务编号,快讯业务编号为B100000,资讯业务编号为B100001
     * //          'tag':'recommend',//资讯标签，可传””。可选值为：recommend(推荐 ) 、dailyMarket（每⽇⾏情）、
     *                    beeMystery（币圈秘事）、noviceRead（新⼿必读）;
     * //          'requestId':'12345678',//每次请求的唯⼀号，字符串⻓度⼩于64
     * //          'id':''//第⼀次请求传””，获取最新发布的10条数据（按照发布时间倒序排列），
     *                      如果还想获取这10条数据发布前的数据，则传⼊第10条的id，依次类推；如果想获取最新的数据，则继续传””。
     *                      建议5分钟调⽤⼀次，每次都传””
     * //       }
     * //   }
     * @return mixed
     */
    public function pull_bkb_news($businessNo = '', $tag = '', $categoryId=0)
    {
        $data = [
            'businessNo' => $businessNo,
            'tag' => $tag,
            'requestId' => md5(Str::random(8) . microtime()),
            'id' => '',
        ];
        $json = json_encode($data);
        $sign = sha512($this->bkbKey . $json);
        $param = [
            'channel' => $this->bkbChannel,
            'sign' => $sign,
            'data' => $data,
        ];
        $header = [
            'Content-Type: application/json; charset=utf-8',
            'Accept: application/json, text/plain, */*'
        ];
        $response = curlPost($this->bkbUrl, json_encode($param), $header, true);
        $result = json_decode($response, true);

        $logMsg = '';
        $update = 0;
        $insert = 0;
        $delete = 0;
        $count = 0;
        $nochange = 0;

        try {
            if (0 == $result['errCode']) {
                if (!empty($result['data']) && !empty($result['data']['body'])) {
                    if (!empty($result['data']['body']['content'])) {
                        $articles = $result['data']['body']['content'];
                        $count = count($articles);

                        foreach ($articles as $article) {
                            //存在封面为资讯,否则为快讯
                            $articleData = [];
                            $publishTime = date('Y-m-d H:i:s', $article['publishDate']/ 1000);
                            if (isset($article['content'])) {
                                //资讯
                                $articleData = [
                                    'user_id' => $this->checkAuthor($article['originName']),
                                    'name' => $article['title'],
                                    'img' => isset($article['coverImgIds'][0]) ? $article['coverImgIds'][0] : '',
                                    'content' => $article['content'],
                                    'number' => $article['pv'],
                                    'category_id' => $categoryId,  //TODO 根据请求tag查询分类ID
                                    'type' => 1, //1为资讯
                                    'tags' => [],
                                    'create_time' => $publishTime,
                                    'update_time' => $publishTime,
                                ];
                            } else {
                                //快讯
                                $articleData = [
                                    'user_id' => 0,
                                    'name' => $article['title'],
                                    'content' => $article['text'],
                                    'img' => '',
                                    'tags' => [],
                                    'category_id' => 0,
                                    'type' => 2, //2为快讯 //TODO articlesType = newsflashes快讯，ad⼴告
                                    'create_time' => $publishTime,
                                    'update_time' => $publishTime,
                                ];
                            }

                            $status = $article['status'];
                            $existInfo = BkbPublishLog::where('bkb_id' ,$article['id'])->first();

                            if ($existInfo) {
                                if ($status == 'update') {
                                    $articleInfo = Article::find($existInfo->article_id);
                                    if ($articleInfo) {
                                        $articleInfo->update($articleData);
                                        $update++;
                                        continue;
                                    }else{
                                        $existInfo->delete();
                                    }
                                }else{
                                    $nochange++;
                                    continue;
                                }
                            }

                            //新增文章
                            $mod = Article::create($articleData);
                            BkbPublishLog::create([
                                'bkb_id' => $article['id'],
                                'article_id' => $mod->id,
                                'businessNo' => $businessNo,
                                'tag' => $tag,
                            ]);
                            $insert++;
                        }
                    } else {
                        $logMsg .= ' 没有获取到资讯';
                    }

                    //待删除文章
                    if (!empty($result['data']['body']['rollback'])) {
                        $rollbacks = $result['data']['body']['rollback'];
                        $delete = count($rollbacks);

//                        foreach ($rollbacks as $rollback) {
                            // 删除文章
//                        }
                        $articleIDs = BkbPublishLog::whereIn('bkb_id', $rollbacks)->pluck('article_id')->toArray();
                        if (!empty($articleIDs)) {
                            Article::whereIn('id', $articleIDs)->delete();
                            BkbPublishLog::whereIn('bkb_id', $rollbacks)->delete();
                        }

                        $logMsg .= ' 资讯删除成功';
                    }else{
                        $logMsg .= ' 没有待删除的资讯';
                    }
                }
            }else{
                $logMsg .= ' 请求错误:'.$result['errMsg'];
            }
            $logMsg .= ' 操作完毕';
        } catch (\Exception $e) {
            $logMsg .= ' 错误: ' . $e->getMessage();
        }

        $date = date('Y-m-d H:i:s');
        $log = <<<EOF
[$date] bkb资讯获取 :
businessNo: [$businessNo]  tag: [$tag]
result: 共获取到: [$count]条资讯, 待删除: [$delete]条, 没有改变: [$nochange]条, 新增成功: [$insert]条, 修改成功: [$update]条
       $logMsg

EOF;
        $this->writeLog($log);

    }

    /**
     * 通过用户nickname查询是否存在，不存在则创建之
     * @param $authorName
     * @return mixed
     */
    private function checkAuthor($authorName)
    {
        $class = new PinYinService();
        $pinyin = $class->str2py($authorName);

        $user = User::firstOrCreate([
            'nickname' => $authorName,
            'username' => 'bkb_author_' . $pinyin,
            'salt' => 'bkbxxx',
            'reg_ip' => 'admin',
        ]);

        return $user ? $user->id : 1;
    }

    /**
     * 记录日志
     * @param $content
     */
    private function writeLog($content): void
    {
        $logFile = fopen(
            storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m-d') . '_bkb_news.log'),
            'a+'
        );
        fwrite($logFile, $content . PHP_EOL);
        fclose($logFile);

    }
}
