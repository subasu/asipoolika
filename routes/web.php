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

Route::get('/', 'TestController@home');
Route::post('save', 'TestController@save');
Route::get('unit_count','IndexController@unit_count');

Auth::routes();

Route::group(['prefix'=>'user'],function() {
    Route::get('/productRequest',[
        'uses'=>'RequestController@productRequestGet',
        'as'=>'productRequest',
        'roles'=>['author','admin']
    ]);
    Route::post('/productRequest','RequestController@productRequestPost');
});

Route::group(['prefix'=>'systemManager'],function() {
    Route::get('/signatures',[
        'uses'=>'SystemManagerController@getSignature',
        'as'=>'signature'
    ]);
    Route::get('add_signature',[
        'uses'=>'SystemManagerController@getAddSignature',
        'as'=>'addSignature'
    ]);
    Route::post('/productRequest','RequestController@productRequestPost');
});

Route::group(['prefix'=>'admin'],function() {

});
Route::get('unit_signature','SystemManagerController@unit_user_list');


Route::get('/home', 'HomeController@index');

Route::get('/form2', function () {
    return view('user.serviceRequest');
});
Route::get('/user', function () {
    return view('user.userCreate');
});
Route::get('/unit', function () {
    return view('user.unitCreate');
});
Route::get('/admin/form1', function () {
    return view('admin.productRequest');
});
Route::get('/admin/form2', function () {
    return view('admin.serviceRequest');
});
Route::get('/admin/form8', function () {
    return view('admin.serviceCertification');
});
Route::get('/admin/form9', function () {
    return view('admin.productDelivery');
});
Route::get('/admin/form6', function () {
    return view('admin.serviceDelivery');
});
Route::get('/admin/form4', function () {
    return view('admin.ceremonial');
});
Route::get('logout',function(){
    Auth::logout();
    return redirect('/login');
});
