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
    Route::post('/login', 'ApiAuthController@login');

    Route::group(['middleware' => ['auth.user']], function () {
        Route::get('/user/logout', 'UserController@logout');

        Route::get('/banners', 'BannerController@table');
        Route::get('/banners/{id}', 'BannerController@info');

        Route::get('/users', 'UserController@table');
        Route::get('/users/{id}', 'UserController@info');
        Route::put('/users/{id}', 'UserController@update');

        Route::get('/tags', 'TagController@table');
        Route::get('/tags/{id}', 'TagController@info');

        Route::get('/hot_words', 'HotWordController@table');
        Route::get('/hot_words/{id}', 'HotWordController@info');

        Route::get('/articles', 'ArticleController@table');
        Route::get('/articles/{id}', 'ArticleController@info');

        Route::get('/categories', 'CategoryController@table');
        Route::get('/categories/group', 'CategoryController@group');
        Route::get('/categories/{id}', 'CategoryController@info');

        Route::get('/subjects', 'SubjectController@table');
        Route::get('/subjects/{id}/articles', 'SubjectController@articles');
        Route::get('/subjects/{id}', 'SubjectController@info');

        Route::get('/pages', 'PageController@table');
        Route::get('/pages/{id}', 'PageController@info');

        Route::get('/notices', 'NoticeController@table');
        Route::get('/notices/{id}', 'NoticeController@info');

//        Route::get('/comments', 'CommentController@table');
//        Route::get('/comments/{id}', 'CommentController@info');
        Route::post('/comments', 'CommentController@save');
//        Route::put('/comments/{id}', 'CommentController@update');
//        Route::delete('/comments', 'CommentController@delete');

    });
});