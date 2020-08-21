<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Users'], function() {
        // views
        Route::group(['prefix' => 'users'], function() {
            
            Route::view('/', 'users.index')->middleware('permission:read-users');
            Route::view('/customer-orders', 'orders.index')->middleware('permission:orders-list');
            Route::view('/create', 'users.create')->middleware('permission:create-users');
            Route::view('/{user}/edit', 'users.edit')->middleware('permission:update-users');
            Route::view('/transactions', 'agents.transactions')->middleware('permission:read-transactions'); 
        });

        // api
        Route::group(['prefix' => 'api/users'], function() {
           
            Route::post("/orders/filter","OrderController@filter")->middleware("permission:orders-list");
            Route::get("/whatsapp/edit","OrderController@getWhatsappData")->middleware("permission:edit-order");
            Route::post("/whatsapp/update-order/{id}","OrderController@updateWhatsappOrder")->middleware("permission:edit-order");
            Route::post("/orders/udpate-status","OrderController@updateStatus")->middleware("permission:update-status-permission");
            Route::get('/getUserRoles/{user}', 'UserController@getUserRoles');
            Route::get('/count', 'UserController@count');
            Route::get('/balance/count', 'UserController@countBalance')->middleware("permission:balance-count");
            Route::post('/filter', 'UserController@filter')->middleware('permission:read-users');
            Route::post('/transactions', 'UserController@transactions')->middleware('permission:read-transactions');

            Route::get('/{user}', 'UserController@show')->middleware('permission:read-users');
            Route::post('/store', 'UserController@store')->middleware('permission:create-users');
            Route::put('/update/{user}', 'UserController@update')->middleware('permission:update-users');
            Route::delete('/{user}', 'UserController@destroy')->middleware('permission:delete-users');
        });
    });
});
