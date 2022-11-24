<?php

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('master-data')->group(function () {
        // เมนูจัดการข้อมูลประเภทรถ
        Route::get('/car_type_information', 'MasterData\Car_informationController@getData');                // ดึงข้อมูลประเภทรถ
        Route::post('/car_type_information', 'MasterData\Car_informationController@insertData');            // บันทึกข้อมูลประเภทรถ
        Route::put('/car_type_information/{id}', 'MasterData\Car_informationController@updateData');        // เปลี่ยนแปลงข้อมูลประเภทรถ
        Route::delete('/car_type_information/{id}', 'MasterData\Car_informationController@deleteData');     // ลบข้อมูลประเภทรถ

        // เมนูจัดการข้อมูลสินค้า
        Route::get('/product_info', 'MasterData\ProductController@getData');                // ดึงข้อมูลสินค้า
        Route::get('/get_product/{id}', 'MasterData\ProductController@get_update');                // ดึงข้อมูลสินค้า
        Route::post('/product_info', 'MasterData\ProductController@insertData');            // บันทึกข้อมูลสินค้า
        Route::put('/product_info/{id}', 'MasterData\ProductController@updateData');        // เปลี่ยนแปลงข้อมูลสินค้า
        Route::get('/check_product_name/{name}', 'MasterData\ProductController@check_product_name');        // เปลี่ยนแปลงข้อมูลสินค้า
        Route::delete('/product_info/{id}', 'MasterData\ProductController@deleteData');     // ลบข้อมูลสินค้า

        // เมนูจัดการข้อมูลรถจัดส่งสินค้า
        Route::get('/delivery_car', 'MasterData\Delivery_carController@getData');                           // ดึงข้อมูลรถจัดส่งสินค้า
        Route::post('/delivery_car', 'MasterData\Delivery_carController@insertData');            // บันทึกข้อมูลรถจัดส่งสินค้า
        Route::put('/delivery_car/{id}', 'MasterData\Delivery_carController@updateData');        // เปลี่ยนแปลงข้อมูลรถจัดส่งสินค้า
        Route::delete('/delivery_car/{id}', 'MasterData\Delivery_carController@deleteData');     // ลบข้อมูลรถจัดส่งสินค้า

        // เมนูจัดการข้อมูลเวลาจัดส่ง
        Route::get('/delivery_time', 'MasterData\Delivery_timeController@getData');
        Route::post('/delivery_time', 'MasterData\Delivery_timeController@insertData');
        Route::put('/delivery_time/{id}', 'MasterData\Delivery_timeController@updateData');
        Route::delete('/delivery_time/{id}', 'MasterData\Delivery_timeController@deleteData');

        // เมนูการจัดการข้อมูลลูกค้า
        Route::get('/customer_information', 'MasterData\Customer_informationController@getData');
        Route::post('/customer_information', 'MasterData\Customer_informationController@insertData');        //บันทึกข้อมูลลูกค้า
        Route::put('/customer_information/{id}', 'MasterData\Customer_informationController@updateData');   //อัพเดทข้อมูลลูกค้า
        Route::delete('/customer_information/{id}', 'MasterData\Customer_informationController@deleteData');   //ลบข้อมูลลูกค้า
        // เมนูจัดการข้อมูลภาษี
        Route::get('/vat', 'MasterData\VatController@getData');
    });

    Route::prefix('master-users')->group(function () {
        // เมนูจัดการข้อมูลผู้ดูแลระบบ
        Route::post('/users', 'MasterUsers\UsersControllser@getData');
    });

    Route::prefix('orders')->group(function () {
        // เมนูจัดการจัดการใบสั่งซื้อ
        Route::post('/getData', 'Orders\OrdersControllser@getData');
        Route::get('/autocomplete', 'Orders\OrdersControllser@search');
        Route::get('/getPrice', 'Orders\OrdersControllser@getPrice');
        Route::get('/searchOther', 'Orders\OrdersControllser@searchOther');
        Route::get('/searchTypeOther', 'Orders\OrdersControllser@searchTypeOther');
        Route::get('/searchSizeOther', 'Orders\OrdersControllser@searchSizeOther');
        Route::post('/cancelorder', 'Orders\OrdersControllser@Cancelorder')->name('oredrs.cancelorder');
        Route::get('/ItemsSize', 'Orders\OrdersControllser@ItemsSize')->name('oredrs.itemssize');
        Route::get('/ItemsSizeNonType', 'Orders\OrdersControllser@ItemsSizeNonType')->name('oredrs.itemssizenontype');
    });

    Route::prefix('delivery')->group(function () {
        //Route::get('/list', 'Delivery\DeliveryController@getList')->name('delivery.list');
	Route::get('/list-custom', 'Delivery\DeliveryController@getList')->name('delivery-list');
        Route::get('/list2', 'Delivery\DeliveryController@getList')->name('pera.list3');
        Route::get('/listitemlist', 'Delivery\DeliveryController@getItemList');
        Route::get('/calendar', 'Delivery\CalendarController@getCalendar');
        Route::get('/master_truck/{id}', 'Delivery\DeliveryController@getMasterTruck');
        Route::get('/OrderDelivery/{id}', 'Delivery\DeliveryController@getOrderDelivery');

        Route::post('/createOrderDelivery', 'Delivery\DeliveryController@createOrderDelivery');
        Route::post('/confirmOrderDelivery', 'Delivery\DeliveryController@confirmOrderDelivery');
        Route::post('/changeOrderDelivery', 'Delivery\DeliveryController@changeOrderDelivery');
        Route::put('/{order_id}/sendOrderDeliverySuccess/{delivery_id}', 'Delivery\DeliveryController@sendOrderDeliverySuccess');
    });

    Route::prefix('claim')->group(function () {
        // เมนูจัดการข้อมูลส่งเคลม
        Route::get('/OrderDelivery/{id}', 'Claim\ClaimController@getOrderDelivery');
        Route::get('/claim', 'Claim\ClaimController@getData');
        Route::get('/master_truck/{id}', 'Claim\ClaimController@getMasterTruck');
        Route::post('/createOrderClaim', 'Claim\ClaimController@createOrderClaim');
        Route::post('/createOrderreturn', 'Claim\ClaimController@createOrderreturn');


    });

    Route::prefix('/noti')->group(function () {
        //notification
        Route::get('/Notification', 'Notification\NotificationController@Notification');
        Route::get('/NotificationCache/{order_id}/{order_delivery_id}/{truck_schedule_id}', 'Notification\NotificationController@NotificationCache');
        Route::get('/NotificationCacheOrder/{order_id}', 'Notification\NotificationController@NotificationCacheOrder');
        Route::get('/NotificationCacheMastertruck/{master_truck_id}', 'Notification\NotificationController@NotificationCacheMastertruck');
    });

    Route::prefix('/report')->group(function () {
        Route::get('/return/list', 'Report\ReportController@returnList')->name('api:report:return:list');
        Route::get('/claim/list', 'Report\ReportController@claimList')->name('api:report:claim:list');
        Route::get('/total/list', 'Report\ReportController@totalList')->name('api:report:total:list');
        Route::get('/transaction/list', 'Report\ReportController@transactionList')->name('api:report:transaction:list');
    });

    Route::prefix('itemsend')->group(function () {

        Route::get('/list', 'MasterData\ItemSendController@getItemList')->name('itemsend.list');
    });
    Route::prefix('itemmerchant')->group(function () {

        Route::get('/list', 'MasterData\ItemMerchantSendController@getItemList')->name('itemmerchant.list');
    });
});
