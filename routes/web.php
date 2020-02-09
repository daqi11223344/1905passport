<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/user','User\IndexController@index');  //用户注册
Route::post('/passport','User\IndexController@pass');   //用户登录
Route::post('/passports','User\IndexController@passd'); 
Route::post('/passportd','User\IndexController@passt');
Route::get('/showData','User\IndexController@showData');    //获取数据接口
Route::post('/auth','User\IndexController@auth');    //获取数据接口
Route::post('/gitpull','User\IndexController@gitpull');
Route::get('/token','User\IndexController@token');

Route::get('/check','Test\TestController@md5post');
Route::post('/checks','Test\TestController@checks');

Route::post('/priv','Test\TestController@priv');
Route::post('/pub','Test\TestController@pub');

Route::get('/rsa1','Test\TestController@rsa1');
Route::get('/rsa2','Test\TestController@rsa2');





