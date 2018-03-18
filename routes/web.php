<?php


Route::get('/', function () {
    return view('welcome');
});
Route::get('/chat', ['uses' => 'ChatController@index', 'as' => 'chat.index']);
Route::get('/user/change-status','ChatController@changeStatus');
Route::get('/chat/list','ChatController@getList');
Route::get('/group/members','GroupController@getMembers');
Route::get('/chat-record','ChatRecordController@index');
Route::get('/chat-record/view','ChatRecordController@view');
Route::get('/user/sign','UserController@updateSign');
Route::get('/user/close','UserController@closeConn');

Route::post('/upload/image','UploadController@image');
Route::post('/upload/file','UploadController@file');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



