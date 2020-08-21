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

Route::get('/', function(){
    return view('welcome');
});
Route::post("send-otp","Users\UserController@sendOtp");
Route::post("verify-otp","Users\UserController@verifyOtp");
Route::post("/whatsapp/complete-order","PdfGenerator@completeWhatsappOrder");
Route::get('/whatsapp', function () {
    return view('Tool.whatsapp');
});
Route::post("/whatsapp/generate-pdf","PdfGenerator@whatsapp");
Route::post("/create-order","pdfGenerator@createOrder");
Route::post("/customer/login","DashboardController@login");

Route::get("/check-login-status","Users\OrderController@checkLoginStatus");
Route::get("/customer/download",function( \Illuminate\Http\Request $request){
    $path=$request->input("link");
    return response()->download($path);
})->middleware("auth");
Route::get("/customer-order/{id}/edit",function(){
    return view('Tool.whatsapp');
                })->middleware("auth")->middleware("permission:edit-order");
//  Route::get("/login","Auth\LoginController@login");
Auth::routes();
Route::get('/home', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', 'DashboardController@index')->name('home');

require __DIR__ . '/profile/profile.php';
require __DIR__ . '/users/users.php';
require __DIR__ . '/roles/roles.php';
require __DIR__ . '/roles/permissions.php';
require __DIR__ . '/modules/modules.php';
require __DIR__ . '/topup/agent.php';
