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

    Route::post('/login/sms', 'ApiAuthController@login_sms');

    //TODO 待调试
    Route::post('/login', 'ApiAuthController@login');

    //=============================需要登陆====================================================
    Route::group(['middleware' => ['api.user']], function () {
        Route::get('/user', 'ApiAuthController@user');
        Route::get('/logout', 'ApiAuthController@logout');

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

    // 行情列表-市值榜
    Route::get('/coins/coinrank', 'CoinController@coinrank');
    // 行情列表-涨跌幅榜
    Route::get('/coins/maxchange', 'CoinController@maxchange');

    Route::get('/coins/{code}/markets', 'CoinController@markets');
    Route::get('/coins/{code}/kline', 'CoinController@kline');
    Route::get('/coins/{code}', 'CoinController@info');



    Route::get('/tags', 'TagController@table');

    Route::get('/users/authors', 'UserController@authors');

    Route::get('/articles/top', 'ArticleController@top');
    Route::get('/articles', 'ArticleController@table');
    Route::get('/comments/{id}/comment', 'CommentController@info_comments');
    Route::get('/articles/{id}/comments', 'CommentController@articleComments');
    Route::get('/articles/{id}', 'ArticleController@info');

    Route::get('/tabbars', 'CategoryController@tabbars');
    Route::get('/banners', 'BannerController@table');



    //TODO 待调试

//    Route::get('/tags', 'TagController@table');
//    Route::get('/tags/{id}', 'TagController@info');
//
//    Route::get('/hot_words', 'HotWordController@table');
//    Route::get('/hot_words/{id}', 'HotWordController@info');
//
//    Route::get('/articles/{id}', 'ArticleController@info');
//
//
//    Route::get('/subjects', 'SubjectController@table');
//    Route::get('/subjects/{id}/articles', 'SubjectController@articles');
//    Route::get('/subjects/{id}', 'SubjectController@info');
//
//    Route::get('/pages', 'PageController@table');
//    Route::get('/pages/{id}', 'PageController@info');
//
//    Route::get('/notices', 'NoticeController@table');
//    Route::get('/notices/{id}', 'NoticeController@info');

});
