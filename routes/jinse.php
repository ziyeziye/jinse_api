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
    Route::post('/common/send_sms', 'CommonController@sendSms');
    Route::post('/common/check_sms', 'CommonController@checkSms');

    Route::post('/login/sms', 'ApiAuthController@login_sms');
    Route::post('/login', 'ApiAuthController@login');

    //=============================需要登陆====================================================
    Route::group(['middleware' => ['api.user']], function () {
        Route::get('/user', 'ApiAuthController@user');
        Route::get('/logout', 'ApiAuthController@logout');
        Route::put('/user/password', 'ApiAuthController@password');
        Route::put('/user/phone', 'ApiAuthController@phone');
        Route::put('/user/modify', 'ApiAuthController@modify');

        Route::post('/comments', 'CommentController@save');
        Route::put('/comments/{id}/zan', 'CommentController@zan');

        Route::put('/articles/{id}/zan', 'ArticleController@zan');
        Route::put('/articles/{id}/good', 'ArticleController@good');
        Route::put('/articles/{id}/bad', 'ArticleController@bad');
        Route::put('/articles/{id}/collect', 'ArticleController@collect');
        Route::get('/articles/follow/author', 'ArticleController@follow_author');
        Route::get('/articles/follow/tag', 'ArticleController@follow_tag');

        Route::put('/users/{id}/follow', 'UserController@follow_add');
        Route::get('/users/follows', 'UserController@follows');
        Route::get('/users/fans', 'UserController@fans');
        Route::get('/users/follows', 'UserController@follows');
        // 用户自选
        Route::put('/users/{code}/coins', 'UserController@coinfocus');
        // 用户自选列表
        Route::get('/users/coinfocus', 'CoinController@coinfocus');

        Route::get('/collections', 'CollectionController@table');

        Route::get('/histories', 'HistoryController@table');
        Route::post('/histories', 'HistoryController@save');

        Route::put('/tags/{id}/follow', 'TagController@follow_add');

    });

    //=============================不需要登陆====================================================

    // 搜索资讯
    Route::get('/search/search', 'SearchController@search');
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



    Route::get('/tags', 'TagController@table');
    Route::get('/users/authors', 'UserController@authors');
    Route::get('/articles/top', 'ArticleController@top');
    Route::get('/articles', 'ArticleController@table');
    Route::get('/comments/{id}/comment', 'CommentController@info_comments');
    Route::get('/articles/{id}/comments', 'CommentController@articleComments');
    Route::get('/articles/{id}', 'ArticleController@info');
    Route::get('/tabbars', 'CategoryController@tabbars');
    Route::get('/banners', 'BannerController@table');
    Route::get('/hot_words', 'HotWordController@table');
    Route::get('/pages/{type}', 'PageController@pageType');
    Route::post('/feedbacks', 'FeedbackController@save');

    //TODO 待调试
//    Route::get('/subjects', 'SubjectController@table');
//    Route::get('/subjects/{id}/articles', 'SubjectController@articles');
//    Route::get('/subjects/{id}', 'SubjectController@info');
//
//    Route::get('/notices', 'NoticeController@table');
//    Route::get('/notices/{id}', 'NoticeController@info');

});
