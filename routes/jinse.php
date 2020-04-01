<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

Route::group(['prefix' => '/api'], function () {
//    Route::get('/common/captcha', 'CommonController@getCaptcha');
    Route::post('/common/upload', 'CommonController@upload');
    //发送短信
    Route::post('/common/send_sms', 'CommonController@sendSms');
    //验证短信
    Route::post('/common/check_sms', 'CommonController@checkSms');
    //短信登录
    Route::post('/login/sms', 'ApiAuthController@login_sms');
    //密码登录
    Route::post('/login', 'ApiAuthController@login');

    //=============================需要登陆====================================================
    Route::group(['middleware' => ['api.user']], function () {
        //登录用户信息
        Route::get('/user', 'ApiAuthController@user');
        //退出登录
        Route::get('/logout', 'ApiAuthController@logout');
        //修改密码
        Route::put('/user/password', 'ApiAuthController@password');
        //更改手机
        Route::put('/user/phone', 'ApiAuthController@phone');
        //修改登录用户资料
        Route::put('/user/modify', 'ApiAuthController@modify');
        //添加评论
        Route::post('/comments', 'CommentController@save');
        //评论点赞
        Route::put('/comments/{id}/zan', 'CommentController@zan');
        //回复我的
        Route::get('/comments/reply_msg', 'CommentController@reply_msg');
        //点赞我的
        Route::get('/comments/zan_msg', 'CommentController@zan_msg');
        //文章点赞
        Route::put('/articles/{id}/zan', 'ArticleController@zan');
        //文章利好
        Route::put('/articles/{id}/good', 'ArticleController@good');
        //文章利空
        Route::put('/articles/{id}/bad', 'ArticleController@bad');
        //收藏文章
        Route::put('/articles/{id}/collect', 'ArticleController@collect');
        //获取关注用户文章
        Route::get('/articles/follow/author', 'ArticleController@follow_author');
        //获取关注标签文章
        Route::get('/articles/follow/tag', 'ArticleController@follow_tag');
        //关注用户
        Route::put('/users/{id}/follow', 'UserController@follow_add');
        //我的关注
        Route::get('/users/follows', 'UserController@follows');
        //我的粉丝
        Route::get('/users/fans', 'UserController@fans');
        //用户自选
        Route::put('/users/{code}/coins', 'UserController@coinfocus');
        //用户自选列表
        Route::get('/users/coinfocus', 'CoinController@coinfocus');
        //我的文章收藏列表
        Route::get('/collections', 'CollectionController@table');
        //浏览历史
        Route::get('/histories', 'HistoryController@table');
        //添加浏览历史
        Route::post('/histories', 'HistoryController@save');
        //关注标签
        Route::put('/tags/{id}/follow', 'TagController@follow_add');
        //系统公告
        Route::get('/notices', 'NoticeController@table');



    });

    //=============================不需要登陆====================================================


    //订阅.标签列表
    Route::get('/tags', 'TagController@table');
    //订阅.作者列表
    Route::get('/users/authors', 'UserController@authors');
    //获取用户/作者信息
    Route::get('/users/{id}', 'UserController@getUser');
    //top类文章
    Route::get('/articles/top', 'ArticleController@top');
    //获取分类文章列表
    Route::get('/articles', 'ArticleController@table');
    //评论详情列表(子评论)
    Route::get('/comments/{id}/comment', 'CommentController@info_comments');
    //文章评论列表
    Route::get('/articles/{id}/comments', 'CommentController@articleComments');
    //文章详情
    Route::get('/articles/{id}', 'ArticleController@info');
    //获取分类(tabbar)
    Route::get('/tabbars', 'CategoryController@tabbars');
    //获取banner
    Route::get('/banners', 'BannerController@table');
    //获取热词列表
    Route::get('/hot_words', 'HotWordController@table');
    //获取单页信息(隐私政策,服务协议...)
    Route::get('/pages/{type}', 'PageController@pageType');
    //添加反馈
    Route::post('/feedbacks', 'FeedbackController@save');
    // 搜索资讯
    Route::get('/search/search', 'SearchController@search');

    //=====================================货币相关接口start=========================================

    // 搜索货币/交易所
    Route::get('/search/coins', 'CoinController@search');
    // 行情列表-市值榜
    Route::get('/coins/coinrank', 'CoinController@coinrank');
    // 涨跌幅榜
    Route::get('/coins/maxchange', 'CoinController@maxchange');
    // 市场交易对
    Route::get('/coins/{code}/markets', 'CoinController@markets');
    // 全球均线
    Route::get('/coins/{code}/kline', 'CoinController@kline');
    // 货币详情
    Route::get('/coins/{code}', 'CoinController@info');
    // 交易所详情
    Route::get('/coins/exchange/{code}', 'CoinController@exchange_info');
    // 交易所公告
    Route::get('/coins/exchange/{code}/news', 'CoinController@exchange_news');
    // 交易所支持的交易对
    Route::get('/coins/exchange/{code}/markets', 'CoinController@exchange_markets');

    //=====================================货币相关接口end=========================================


});
