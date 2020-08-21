<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Agents'], function() {
        // views
        
        Route::group(['prefix' => 'agents'], function() {
            Route::view('/topup', 'agents.index')->middleware('permission:read-topup'); 
            Route::view('/topup/create', 'agents.create')->middleware('permission:create-topup');
            
        });

        // api
        Route::group(['prefix' => 'api/agents'], function() {
            Route::get('/getUserRoles/{user}', 'UserController@getUserRoles');
            Route::get('/count', 'UserController@count');
            
            Route::post('/topup/filter', 'AgentController@topupFilter')->middleware('permission:read-topup');
            Route::get('/{user}', 'UserController@show')->middleware('permission:read-users');
            Route::post('/topup/store', 'AgentController@storeTopup')->middleware('permission:create-topup');
            Route::put('/update/{user}', 'UserController@update')->middleware('permission:update-users');
            Route::delete('/{user}', 'UserController@destroy')->middleware('permission:delete-users');
        });
    });
});
