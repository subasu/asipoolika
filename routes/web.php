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
Route::get('/unit_count','IndexController@unit_count');

Auth::routes();

Route::group(['prefix'=>'user'],function() {
    Route::get('/productRequest',[
        'uses'=>'RequestController@productRequestGet',
        'as'=>'productRequest',
        'roles'=>['author','admin']
    ]);
    Route::post('/productRequest','RequestController@productRequestPost');
    //shiri
    Route::get('/serviceRequest','RequestController@serviceRequestGet');
    Route::post('serviceRequest','RequestController@serviceRequest');
    Route::get('/ticketRequest','RequestController@ticketRequest');                         //96/7/2
    Route::get('/getUnits','RequestController@getUnits');                                   //96/7/2
    Route::post('sendTicket','RequestController@sendTicket');                               //96/7/3
    Route::get('ticketsManagement','RequestController@ticketsManagement');                  //96/7/3
    Route::post('searchOnDate/{id}','RequestController@searchOnDate');                      //96/7/3
    Route::get('ticketConversation/{id}','RequestController@ticketConversation');          //96/7/3
    Route::post('userSendMessage','RequestController@userSendMessage');                    //96/7/4
    Route::post('userEndTicket' , 'RequestController@userEndTicket');                      //96/7/5


});

Route::group(['prefix'=>'systemManager'],function() {
    Route::get('/signatures',[
        'uses'=>'SystemManagerController@getSignatures',
        'as'=>'signature'
    ]);
    Route::get('add_signature',[
        'uses'=>'SystemManagerController@getAddSignature',
        'as'=>'addSignature'
    ]);
    Route::get('edit_signature/{id}',[
        'uses'=>'SystemManagerController@getEditSignature',
        'as'=>'editSignature'
    ]);
    Route::post('/productRequest','RequestController@productRequestPost');
    //shiri
    Route::post('addSignature','SystemManagerController@addSignature');                      //96/7/6
    Route::get('showSignature/{id}','SystemManagerController@showSignature');                //96/7/6
    Route::post('makeSignatureForced' , 'SystemManagerController@makeSignatureForced');      //96/7/6
    route::post('makeSignatureUnforced' , 'SystemManagerController@makeSignatureUnforced');  //96/7/6
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix'=>'admin'],function() {
        //Product Request Management
        Route::get('/productRequestManagement','SupplyController@productRequestManagement');
        Route::get('/productRequestRecords/{id}','SupplyController@productRequestRecords');
        Route::get('/acceptProductRequestManagement','SupplyController@acceptProductRequestManagementGet');
        Route::post('acceptProductRequest','SupplyController@acceptProductRequest');
        Route::post('refuseRequestRecord','SupplyController@refuseRequestRecord');
        Route::get('/refusedProductRequestManagement','SupplyController@refusedProductRequestManagementGet');
        Route::get('/confirmProductRequestManagement','SupplyController@confirmProductRequestManagementGet');

        Route::get('/impart/{id}','CertificateController@impartGet');
        Route::get('/impart','CertificateController@impart');
        Route::get('/certificate/{id}','CertificateController@execute_certificateGet');
        Route::post('/execute_certificate','CertificateController@execute_certificate');
        Route::get('/certificatesManagement','CertificateController@certificatesManagementGet');
        Route::get('/certificateRecords/{id}','CertificateController@certificateRecordsGet');
        Route::post('/acceptCertificate','CertificateController@acceptCertificate');

        //End Product Request Management
        Route::get('/serviceRequestManagement','SupplyController@serviceRequestManagement');

        //shiri
        Route::get('recentlyAddedService','SupplyController@recentlyAddedService');  //96/6/25
        Route::get('serviceRequestRecords/{id}','SupplyController@serviceRequestRecords'); //96/6/26

        Route::post('acceptServiceRequest','SupplyController@acceptServiceRequest');  //96/6/26

        Route::get('showToCreditManager','CertificateController@showToCreditManager');
        Route::get('workerCardCreate' ,'SupplyController@workerCardCreate');        //96/7/1
        Route::post('addWorkerCard' ,'SupplyController@addWorkerCard');            //96/7/1
        Route::post('addWorkerCard' ,'SupplyController@addWorkerCard');             //96/7/1

        Route::get('workerCardManage' ,'SupplyController@workerCardManage');                         //96/7/2
        Route::post('searchOnDate/{id}' ,'SupplyController@searchOnDate');                           //96/7/2
        Route::get('showWorkerCard/{id}','SupplyController@showWorkerCard');                         //96/7/2
        Route::get('showTickets','SupplyController@showTickets');                                    //96/7/5
        Route::post('adminSendMessage','SupplyController@adminSendMessage');                        //96/7/5
        Route::post('adminEndTicket','SupplyController@adminEndTicket');                            //96/7/5
        Route::get('printProductRequest/{id}','SupplyController@printProductRequest');              //96/7/11
        Route::post('newUserCreate','SupplyController@newUserCreate');                              //96/7/12
        Route::post('newUserWithUnitManager','SupplyController@newUserWithUnitManager');            //96/7/12
        Route::post('newUserWithoutUnitManager','SupplyController@newUserWithoutUnitManager');      //96/7/12
        // Route::get('kiayanfar','RequestController@kiyanfar');


        //rayat - users manage:
        Route::get('usersManagement', 'SupplyController@usersManagementGet');
        Route::get('usersCreate', 'SupplyController@usersCreateGet');
        Route::post('checkUnitSupervisor', 'SupplyController@checkUnitSupervisor');
        Route::post('changeUserStatus/{id}', 'SupplyController@changeUserStatus');
        Route::get('usersUpdate/{id}', 'SupplyController@usersUpdateShow');
        Route::post('usersUpdate', 'SupplyController@usersUpdate');

        //rayat - units manage:
        Route::get('unitsManage', 'SupplyController@unitsManageGet');
        Route::get('unitsCreate', 'SupplyController@unitsCreateGet');
        Route::post('unitsCreate', 'SupplyController@unitsCreatePost');
        Route::get('units', 'SupplyController@unitsGet');
        Route::post('changeUnitStatus/{id}', 'SupplyController@changeUnitStatus');
        Route::get('unitsUpdate/{id}', 'SupplyController@unitsUpdateShow');
        Route::post('unitsUpdate', 'SupplyController@unitsUpdate');
        Route::post('usersSupervisor', 'SupplyController@usersSupervisor');
    });
});

Route::get('unit_signature','SystemManagerController@unit_user_list');
Route::get('price','IndexController@ajaxPrice');

Route::get('/home', 'HomeController@index');

Route::get('/f', function () {
    return view('forms.productRequestForm');
});

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
