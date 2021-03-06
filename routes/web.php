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

Route::get('/', 'IndexController@goToLoginPage');
Route::post('save', 'TestController@save');
Route::get('/unit_count','IndexController@unit_count');
Route::get('403',function(){
    return view('errors.403');
});
Auth::routes();
Route::group(['middleware' => 'auth'], function () {
Route::group(['prefix'=>'user'],function() {

    Route::get('changePassword','RequestController@changePassword');

    Route::get('/productRequest',[
        'uses'=>'RequestController@productRequestGet',
        'middleware' => 'roles',
        'roles'=>['supplierManager','unitManager','user','systemManager']
    ]);
    Route::post('/productRequest','RequestController@productRequestPost');
//    Route::get('/productRequestFollow','RequestController@productRequestFollowGet');
    Route::get('/productRequestFollow',[
        'uses'=>'RequestController@productRequestFollowGet',
        'middleware' => 'roles',
        'roles'=>['supplierManager','unitManager','user','systemManager']
    ]);

    //shiri
//    Route::get('/serviceRequest','RequestController@serviceRequestGet');
    Route::get('/serviceRequest',[
        'uses'=>'RequestController@serviceRequestGet',
        'middleware' => 'roles',
        'roles'=>['supplierManager','unitManager','user','systemManager']
    ]);
    Route::post('serviceRequest','RequestController@serviceRequest');

//    Route::get('/serviceRequestFollow','RequestController@serviceRequestFollowGet');
    Route::get('/serviceRequestFollow',[
        'uses'=>'RequestController@serviceRequestFollowGet',
        'middleware' => 'roles',
        'roles'=>['supplierManager','unitManager','user','systemManager']
    ]);
//    Route::get('/myRequestRecords/{id}','RequestController@myRequestRecordsGet');
    Route::get('/myRequestRecords/{id}',[
        'uses'=>'RequestController@myRequestRecordsGet',
        'middleware' => 'roles',
        'roles'=>['supplierManager','unitManager','user','systemManager']
    ]);



    Route::get('/ticketRequest','RequestController@ticketRequest');
//    Route::get('/getUnits','RequestController@getUnits');
    Route::post('sendTicket','RequestController@sendTicket');
    Route::get('ticketsManagement/{id}','RequestController@ticketsManagement');
    Route::post('searchOnDate/{id}','RequestController@searchOnDate');
    Route::get('ticketConversation/{id}','RequestController@ticketConversation');
    Route::post('userSendMessage','RequestController@userSendMessage');
    Route::post('endTicket' , 'RequestController@endTicket');

    Route::get('dailyWorks/{parameter}','RequestController@dailyWorks');
    Route::get('dailyWorksDetails/{id}','RequestController@dailyWorksDetails');
    Route::get('showFactorDetails/{id}','RequestController@showFactorDetails');
    Route::post('acceptPreparedSummarize','RequestController@acceptPreparedSummarize');

    //
    Route::post('saveNewPassword','RequestController@saveNewPassword');

});

Route::group(['prefix'=>'systemManager'],function() {

//    Route::get('/signatures',[
//        'uses'=>'SystemManagerController@getSignatures',
//        'as'=>'signature',
//        'roles'=>['systemManager']
//    ]);

//    Route::get('signaturesList','SystemManagerController@signaturesList');

    Route::post('newRole','SystemManagerController@newRole');
    Route::post('deleteRole','SystemManagerController@deleteRole');

    Route::get('/signaturesList',[
        'uses'=>'SystemManagerController@signaturesList',
        'middleware' => 'roles',
        'roles'=>['systemManager']
    ]);

    Route::get('add_signature',[
        'uses'=>'SystemManagerController@getAddSignature',
        'middleware' => 'roles',
        'roles'=>['systemManager']
    ]);
    Route::get('edit_signature/{id}',[
        'uses'=>'SystemManagerController@getEditSignature',
        'middleware' => 'roles',
        'roles'=>['systemManager']
    ]);
    //Shiri
    Route::post('addSignature','SystemManagerController@addSignature');                      //96/7/6

    Route::get('showSignature/{id}',[
        'uses'=>'SystemManagerController@showSignature',
        'middleware' => 'roles',
        'roles'=>['systemManager']
    ]);

    Route::post('makeSignatureForced' , 'SystemManagerController@makeSignatureForced');      //96/7/6
    route::post('makeSignatureUnforced' , 'SystemManagerController@makeSignatureUnforced');  //96/7/6

    Route::get('access_level/{id}',[
        'uses'=>'SystemManagerController@access_levelGet',
        'middleware' => 'roles',
        'roles'=>['systemManager']
    ]);
});

    Route::group(['prefix'=>'admin'],function() {
        // Product Request Management
//        Route::get('/productRequestManagement','SupplyController@productRequestManagement');
        Route::get('/productRequestManagement',[
            'uses'=>'SupplyController@productRequestManagement',
            'middleware' => 'roles',
            'roles'=>['supplierManager','unitManager']
        ]);

//        Route::get('/productRequestRecords/{id}','SupplyController@productRequestRecords');
        Route::get('/productRequestRecords/{id}',[
            'uses'=>'SupplyController@productRequestRecords',
            'middleware' => 'roles',
            'roles'=>['supplierManager','unitManager']
        ]);
//        Route::get('/acceptProductRequestManagement','SupplyController@acceptProductRequestManagementGet');
        Route::get('/acceptProductRequestManagement',[
            'uses'=>'SupplyController@acceptProductRequestManagementGet',
            'middleware' => 'roles',
            'roles'=>['supplierManager','unitManager']
        ]);
        Route::get('/acceptedRequestRecords/{id}',[
            'uses'=>'RequestController@myRequestRecordsGet',
            'middleware' => 'roles',
            'roles'=>['supplierManager','unitManager','systemManager']
        ]);
        Route::post('acceptProductRequest','SupplyController@acceptProductRequest');
        Route::post('refuseRequestRecord','SupplyController@refuseRequestRecord');
        Route::get('/refusedProductRequestManagement','SupplyController@refusedProductRequestManagementGet');
//        Route::get('/confirmProductRequestManagement','SupplyController@confirmProductRequestManagementGet');
        Route::get('/confirmProductRequestManagement',[
            'uses'=>'SupplyController@confirmProductRequestManagementGet',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);

        Route::get('/confirmedRequestDetails/{id}',[
            'uses'=>'SupplyController@confirmedRequestDetails',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);


        // Certificate
        Route::get('/impart/{id}','CertificateController@impartGet');

        Route::get('/impart/{id}',[
            'uses'=>'CertificateController@impartGet',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);

//        Route::get('/impart','CertificateController@impart');
        Route::get('/impart',[
            'uses'=>'CertificateController@impart',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);

//        Route::get('/certificate/{id}','CertificateController@execute_certificateGet');
        Route::get('/certificate/{id}',[
            'uses'=>'CertificateController@execute_certificateGet',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);
        Route::post('/execute_certificate','CertificateController@execute_certificate');
        Route::post('/deliver_to_store','CertificateController@deliver_to_store');

        Route::get('/productCertificatesManagement','CertificateController@productCertificatesManagementGet');
        Route::get('/serviceCertificatesManagement','CertificateController@serviceCertificatesManagementGet');
        Route::get('/acceptedCertificatesManagement','CertificateController@acceptedCertificatesManagementGet');
        Route::get('/productCertificateRecords/{id}','CertificateController@productCertificateRecordsGet');
        Route::get('/serviceCertificateRecords/{id}','CertificateController@serviceCertificateRecordsGet');
        Route::post('/acceptProductCertificate','CertificateController@acceptProductCertificate');
        Route::post('/acceptServiceCertificate','CertificateController@acceptServiceCertificate');
        // End Product Request Management
        // Service Request Management
        Route::get('/serviceRequestManagement','SupplyController@serviceRequestManagement');
        Route::get('/serviceRequestRecords/{id}','SupplyController@serviceRequestRecords');
        Route::get('/acceptServiceRequestManagement','SupplyController@acceptServiceRequestManagementGet');
        Route::post('acceptServiceRequest','SupplyController@acceptServiceRequest');
//        Route::get('/confirmServiceRequestManagement','SupplyController@confirmServiceRequestManagementGet');
        Route::get('/confirmServiceRequestManagement',[
            'uses'=>'SupplyController@confirmServiceRequestManagementGet',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);
        //End Service Request Management

        //shiri
        Route::get('recentlyAddedService','SupplyController@recentlyAddedService');  //96/6/25
        Route::get('serviceRequestRecords/{id}','SupplyController@serviceRequestRecords'); //96/6/26

        Route::post('acceptServiceRequest','SupplyController@acceptServiceRequest');  //96/6/26


        Route::get('showToCreditManager','CertificateController@showToCreditManager');
        Route::get('workerCardCreate' ,'SupplyController@workerCardCreate');        //96/7/1
        //Route::post('addWorkerCard' ,'SupplyController@addWorkerCard');            //96/7/1
        Route::post('addWorkerCard' ,'SupplyController@addWorkerCard');             //96/7/1

//        Route::get('workerCardManage' ,'SupplyController@workerCardManage');                         //96/7/2
        Route::get('/workerCardManage',[
            'uses'=>'SupplyController@workerCardManage',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);
        Route::post('searchOnDate/{id}' ,'SupplyController@searchOnDate');                           //96/7/2

       // Route::get('showWorkerCard/{id}','SupplyController@showWorkerCard');                         //96/7/2
        //Route::get('showTickets','SupplyController@showTickets');                                    //96/7/5

//        Route::get('showWorkerCard/{id}','SupplyController@showWorkerCard');                         //96/7/2
        Route::get('/showWorkerCard/{id}',[
            'uses'=>'SupplyController@showWorkerCard',
            'middleware' => 'roles',
            'roles'=>['supplierManager']
        ]);
        Route::get('showTickets','SupplyController@showTickets');                                    //96/7/5

        Route::post('adminSendMessage','SupplyController@adminSendMessage');                        //96/7/5
        //Route::post('adminEndTicket','SupplyController@adminEndTicket');                            //96/7/5


        Route::get('printProductRequest/{id}','SupplyController@printProductRequest');                           //96/7/11
        Route::post('newUserCreate/{id}','SupplyController@newUserCreate');                                      //96/7/12
        Route::get('exportDeliveryInstallCertificate/{id}','SupplyController@exportDeliveryInstallCertificate'); //96/7/13

        Route::post('formSave/{id}','SupplyController@formSave');                                                     //96/7/14

        Route::get('showCertificates/{id}','SupplyController@showCertificates');                                //96/7/14
        Route::get('printServiceRequest/{id}','SupplyController@printServiceRequest');                          //96/7/15

        Route::get('serviceDeliveryForm/{id}','SupplyController@printServiceDeliveryForm');                     //96/7/15
        Route::get('printFactors/{id}','SupplyController@printFactors');                                        //96/7/16
        Route::get('costDocumentForm/{id}','SupplyController@costDocumentForm');                                //96/7/17
        Route::post('saveCostDocument','SupplyController@saveCostDocument');
        Route::get('productDeliveryAndUseForm/{id}','SupplyController@productDeliveryAndUseForm');


//        Route::get('issueBill','SupplyController@issueBillGet');
//        Route::get('billUpload/{id}','SupplyController@billUpload');
//        Route::post('saveBill','SupplyController@saveBill');

        Route::get('issueBillManagement','SupplyController@issueBillManagementGet');
        Route::get('issueBill/{id}','SupplyController@issueBillGet');
        Route::post('addBillPhoto','SupplyController@addBillPhoto');

        Route::get('preparedSummarize/{id}','SupplyController@preparedSummarize');
        Route::post('savePreparedSummarize','SupplyController@savePreparedSummarize');
        Route::post('updatePreparedSummarize','SupplyController@updatePreparedSummarize');

        Route::post('checkPreparedSummarize','SupplyController@checkPreparedSummarize');

        Route::get('warehouseBill/{id}','SupplyController@warehouseBill');
        Route::post('checkFile/{parameter}','SupplyController@checkFile');

        //rayat - users manage:
//        Route::get('usersManagement', 'SupplyController@usersManagementGet');
        Route::get('/usersManagement',[
            'uses'=>'SupplyController@usersManagementGet',
            'middleware' => 'roles',//meeeeeee
            'roles'=>['systemManager']
        ]);
//        Route::get('usersCreate', 'SupplyController@usersCreateGet');
        Route::get('/usersCreate',[
            'uses'=>'SupplyController@usersCreateGet',
            'middleware' => 'roles',///////meeeeeeeeee
            'roles'=>['systemManager']
        ]);
        Route::post('checkUnitSupervisor', 'SupplyController@checkUnitSupervisor');
        Route::post('changeUserStatus/{id}', 'SupplyController@changeUserStatus');
//        Route::get('usersUpdate/{id}', 'SupplyController@usersUpdateShow');
        Route::get('/usersUpdate/{id}',[
            'uses'=>'SupplyController@usersUpdateShow',
            'middleware' => 'roles',
            'roles'=>['systemManager']
        ]);
        Route::post('usersUpdate', 'SupplyController@usersUpdate');

        //rayat - units manage:
//        Route::get('unitsManage', 'SupplyController@unitsManageGet');
        Route::get('/unitsManage',[
            'uses'=>'SupplyController@unitsManageGet',
            'middleware' => 'roles',
            'roles'=>['systemManager']
        ]);
//        Route::get('unitsCreate', 'SupplyController@unitsCreateGet');
        Route::get('/unitsCreate',[
            'uses'=>'SupplyController@unitsCreateGet',
            'middleware' => 'roles',
            'roles'=>['systemManager']
        ]);
        Route::post('unitsCreate', 'SupplyController@unitsCreatePost');
        Route::get('units', 'SupplyController@unitsGet');
        Route::get('units', 'SupplyController@unitsGet');
        Route::post('changeUnitStatus/{id}', 'SupplyController@changeUnitStatus');
//        Route::get('unitsUpdate/{id}', 'SupplyController@unitsUpdateShow');
        Route::get('/unitsUpdate/{id}',[
            'uses'=>'SupplyController@unitsUpdateShow',
            'middleware' => 'roles',
            'roles'=>['systemManager']
        ]);
        Route::post('unitsUpdate', 'SupplyController@unitsUpdate');
        Route::post('usersSupervisor', 'SupplyController@usersSupervisor');
    });
});

Route::get('unit_signature','SystemManagerController@unit_user_list');
Route::get('price','IndexController@ajaxPrice');



Route::group(['prefix'=>'special'],function() {
    // Product Request Management
    Route::get('/productRequest',[
        'uses'=>'SpecialController@productRequestGet',
        'middleware' => 'roles',
        'roles'=>['special']
    ]);
    Route::get('/serviceRequest',[
        'uses'=>'SpecialController@serviceRequestGet',
        'middleware' => 'roles',
        'roles'=>['special']
    ]);

    Route::get('/activeCertificates/{id}',[
        'uses'=>'SpecialController@activeCertificatesGet',
        'middleware' => 'roles',
        'roles'=>['special']
    ]);

    Route::post('productRequest', 'SpecialController@productRequest');
    Route::post('serviceRequest', 'SpecialController@serviceRequest');
    Route::post('usersSupervisor', 'SpecialController@usersSupervisor');
});



Route::get('/units', 'SpecialController@unitsGet');
//for product unit users+store user
Route::get('/receiver', 'SpecialController@receiverGet');
//for service only the unit users
Route::get('/receiver2', 'SpecialController@receiverGet2');

//Route::get('/f','IndexController@userList');

Route::get('logout',function(){
    Auth::logout();
    return redirect('/login');
});
