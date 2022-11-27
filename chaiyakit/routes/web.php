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



Auth::routes();

Route::get('migrate',function(){
    \Artisan::call('migrate');
});
Route::get('clear/cache',function(){
    \Artisan::call('cache:clear');
});

//Close Register

Route::match(['get', 'post'], 'register', function () {

    return redirect('login');
});



Route::get('/', function () {

    return redirect('login');
});



Route::group(['middleware' => ['auth']], function () {



    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/test', 'Notification\NotificationController@test');



    Route::group(['middleware' => ['role:super-admin|manager|admin']], function () {

        Route::prefix('delivery')->group(function () {

            Route::get('/', 'Delivery\DeliveryController@index')->name('delivery');

            Route::get('/list', 'Delivery\DeliveryController@list')->name('delivery.list');


            Route::get('/list/order-detail/{id}', 'Delivery\DeliveryController@orderDetail')->name('delivery.list.order-detail');

            Route::get('/list/delivery/{id}', 'Delivery\DeliveryController@deliveryList')->name('delivery.list.delivery');

            Route::get('/list/delivery/{id}/send/{delivery_id}', 'Delivery\DeliveryController@deliverySend')->name('delivery.list.delivery.send');

            Route::get('/list/delivery/{id}/confirm/{delivery_id}', 'Delivery\DeliveryController@deliveryConfirm')->name('delivery.list.delivery.confirm');

            Route::get('/list/delivery/{id}/summary/{delivery_id}', 'Delivery\DeliveryController@deliverySummary')->name('delivery.list.delivery.summary');

            Route::get('/calendar', 'Delivery\CalendarController@index')->name('delivery.calendar');


        });





        Route::prefix('master-data')->group(function () {

            Route::get('/product_information', 'MasterData\ProductController@index')->name('product_information');

            Route::get('/product_information/create', 'MasterData\ProductController@create')->name('product_information.create');

            Route::get('/product_information/update/{id}', 'MasterData\ProductController@update')->name('product_information.update');



            Route::get('/car_type_information', 'MasterData\Car_informationController@index')->name('car_type_information');

            Route::get('/car_type_information/create', 'MasterData\Car_informationController@create')->name('car_type_information.create');

            Route::get('/car_type_information/update/{id}', 'MasterData\Car_informationController@update')->name('car_type_information.update');



            Route::get('/delivery_car', 'MasterData\Delivery_carController@index')->name('delivery_car');

            Route::get('/delivery_car/create', 'MasterData\Delivery_carController@create')->name('delivery_car.create');

            Route::get('/delivery_car/update/{id}', 'MasterData\Delivery_carController@update')->name('delivery_car.update');



            Route::get('/delivery_time', 'MasterData\Delivery_timeController@index')->name('delivery_time');

            Route::get('/delivery_time/create', 'MasterData\Delivery_timeController@create')->name('delivery_time.create');

            Route::get('/delivery_time/update/{id}', 'MasterData\Delivery_timeController@update')->name('delivery_time.update');



            Route::get('/customer_information', 'MasterData\Customer_informationController@index')->name('customer_information');

            Route::get('/customer_information/update/{id}', 'MasterData\Customer_informationController@update')->name('customer_information.update');

            Route::get('/customer_information/create_account', 'MasterData\Customer_informationController@create')->name('customer_information.create_account');



            Route::get('/vat', 'MasterData\VatController@index')->name('vat');

            Route::get('/vat/add', 'MasterData\VatController@add')->name('vat.add');

            Route::post('/vat/create', 'MasterData\VatController@create')->name('vat.create');

            Route::post('/vat/update', 'MasterData\VatController@update')->name('vat.update');

            Route::get('/vat/delete/{id}', 'MasterData\VatController@delete')->name('vat.delete');

            Route::get('/vat/vat_manage/{id}', 'MasterData\VatController@manage')->name('vat.manage');

            Route::prefix('itemsend')->group(function () {

                Route::get('/', 'MasterData\ItemSendController@index')->name('master.itemsend');
                Route::get('/{date}', 'MasterData\ItemSendController@itemdate');

            });

            Route::prefix('itemsend')->group(function () {

                Route::get('/', 'MasterData\ItemSendController@index')->name('master.itemsend');
                Route::get('/{date}', 'MasterData\ItemSendController@itemdate');

            });
            Route::prefix('itemmerchant')->group(function () {

                Route::get('/', 'MasterData\ItemMerchantSendController@index')->name('master.itemmerchant');
                Route::get('/{id}/{date}', 'MasterData\ItemMerchantSendController@itemmerchantdate');

            });

            // Route::get('/itemlist', 'Delivery\DeliveryController@itemlist')->name('delivery.itemlist');
            // Route::get('/itemlistmerchant/{id}/{date}', 'Delivery\DeliveryController@itemlistmerchant')->name('delivery.itemlist.merchant_id');

        });



        Route::prefix('claim')->group(function () {

            Route::get('/claim', 'Claim\ClaimController@index')->name('claim');

            Route::get('/list/claim-detail/{id}', 'Claim\ClaimController@deliveryList')->name('claim.list.order_claim'); // list เคลม บิลเล็ก

            Route::get('/list/{id}/detail', 'Claim\ClaimController@orderDetail')->name('claim.list.claim_return');

            Route::get('/list/{order_id}/detail/{delivery_id}', 'Claim\ClaimController@orderDetailItem')->name('claim.list.detail');

            Route::get('/list/claim/{id}/send/{delivery_id}', 'Claim\ClaimController@Claim_Send')->name('claim.list.claim.send');

            Route::get('/list/claimSend/{id1}/{id2}', 'Claim\ClaimController@claimget_datashow');

            Route::get('/pages/claim/claim_confirm', 'Claim\ClaimController@confirm_create')->name('pages.claim.claim_confirm');



            // 'Claim\ClaimController@claimSend')->name('pages.claim.claimSend')



            // Route::get('/claim/claim_confirm', 'Claim\ClaimController@pages_claim_confirm')->name('pages.claim.claim_confirm');





        });

        Route::prefix('report')->group(function () {
            Route::get('/report_return', 'Report\ReportController@reportReturn')->name('report_return');
            Route::get('/report_claim', 'Report\ReportController@reportClaim')->name('report_claim');
            Route::get('/report_total', 'Report\ReportController@reportTotal')->name('report_total');
            Route::get('/report_transaction', 'Report\ReportController@reportTransaction')->name('report_transaction');
            Route::get('/report_per_sale', 'Report\ReportController@reportPerSale')->name('report_per_sale');

        });
    });

    Route::group(['middleware' => ['role:super-admin|manager']], function () {

        Route::prefix('master-users')->group(function () {

            Route::get('/users', 'MasterUsers\UsersControllser@index')->name('users');

            Route::get('/users/add', 'MasterUsers\UsersControllser@add')->name('users.add');

            Route::post('/users/create', 'MasterUsers\UsersControllser@create')->name('users.create');

            Route::post('/users/update', 'MasterUsers\UsersControllser@update')->name('users.update');

            Route::get('/users/delete/{id}', 'MasterUsers\UsersControllser@delete')->name('users.delete');

            Route::get('/users/users_manage/{id}', 'MasterUsers\UsersControllser@manage')->name('users.manage');
        });
    });





    Route::group(['middleware' => ['role:super-admin|manager|admin|accounting']], function () {
        Route::prefix('orders')->group(function () {
            Route::get('/index', 'Orders\OrdersControllser@index')->name('orders');
            Route::get('/detail/{id}', 'Orders\OrdersControllser@detail')->name('orders.detail');
            Route::get('/summary/{id}/{delivery_id}', 'Orders\OrdersControllser@summary')->name('orders.summary');
        });
    });



    Route::group(['middleware' => ['role:super-admin|manager|admin']], function () {
        Route::prefix('orders')->group(function () {
            Route::get('/add', 'Orders\OrdersControllser@add')->name('orders.add');
            Route::post('/create', 'Orders\OrdersControllser@create')->name('orders.create');
            Route::post('/update', 'Orders\OrdersControllser@update')->name('orders.update');
            Route::get('/edit/{id}', 'Orders\OrdersControllser@edit')->name('orders.edit');
            Route::post('/create/merchant', 'Orders\OrdersControllser@insertMerchant')->name('orders.insertMerchant');
            Route::post('/update/merchant', 'Orders\OrdersControllser@updateMerchant')->name('orders.updateMerchant');
            Route::get('/getMasterMerchantPrice/{id}', 'Orders\OrdersControllser@getMasterMerchantPrice')->name('orders.getMasterMerchantPrice');
            Route::post('/create/other', 'Orders\OrdersControllser@createOther')->name('orders.createOther');
        });
    });

    Route::get('updatedepartment','Orders\OrdersControllser@updatedepartment');
});
